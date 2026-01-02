<?php 
session_start();
require_once '../partials/header.php'; 
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Result.php';

if (!isset($_GET['quiz_id'])) {
    header('Location: dashboard.php');
    exit();
}

$quizId = $_GET['quiz_id'];
$userId = $_SESSION['user_id'];

$resObj = new Result();
$existingResult = $resObj->checkAttempt($userId, $quizId);

if ($existingResult) {
    header("Location: quiz_review.php?result_id=" . $existingResult['id']);
    exit();
}

?>

<div class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-800 to-teal-500 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
            <div>
                <h1 id="quiz-title" class="text-2xl md:text-3xl font-extrabold animate-fadeInDown">Chargement du quiz...</h1>
                <p class="text-teal-100 text-lg mt-1">
                    Question <span id="current-q-num" class="font-bold">-</span> sur <span id="total-q-num">-</span>
                </p>
            </div>
            <div class="text-right bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm">
                <div class="text-xs text-teal-100 uppercase tracking-wider mb-1">Temps restant</div>
                <div id="timer" class="text-3xl font-mono font-bold">00:00</div>
            </div>
        </div>
    </div>

    <!-- Quiz Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-10 border border-gray-200 animate-fadeIn">

            <h3 id="question-text" class="text-xl md:text-2xl font-bold text-gray-900 mb-8 leading-relaxed">
                Veuillez patienter, connexion au serveur...
            </h3>

            <div id="options-container" class="space-y-4"></div>

            <div class="flex justify-between items-center mt-10 pt-6 border-t border-gray-100">
                <button id="btn-prev" onclick="prevQuestion()" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 font-medium hover:bg-gray-50 transition flex items-center hidden">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>

                <button id="btn-next" onclick="nextQuestion()" class="px-8 py-3 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-md transition flex items-center transform hover:-translate-y-0.5 hidden">
                    Suivant <i class="fas fa-arrow-right ml-2"></i>
                </button>

                <button id="btn-finish" onclick="submitQuiz()" class="px-8 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700 shadow-md transition flex items-center hidden">
                    Terminer <i class="fas fa-check ml-2"></i>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    const quizId = <?php echo $quizId; ?>;
    let questions = [], currentIndex = 0, userAnswers = {}, timerInterval;

    document.addEventListener('DOMContentLoaded', () => {
        const formData = new FormData();
        formData.append('action','get_questions');
        formData.append('quiz_id',quizId);

        fetch('quiz_ajax_handler.php',{method:'POST', body:formData})
        .then(r=>r.json())
        .then(data=>{
            if(data.success){
                questions = data.questions;
                if(data.quiz_info) document.getElementById('quiz-title').innerText = data.quiz_info.titre;
                document.getElementById('total-q-num').innerText = questions.length;
                if(questions.length>0) renderQuestion();
                else document.getElementById('question-text').innerText="Ce quiz ne contient aucune question.";
            } else alert("Erreur Serveur: "+data.message);
        })
        .catch(e=>{
            console.error(e);
            document.getElementById('question-text').innerText="Erreur de connexion : "+e.message;
            document.getElementById('question-text').classList.add('text-red-600');
        });
    });

    function renderQuestion() {
        const q = questions[currentIndex];
        document.getElementById('question-text').innerText=q.question;
        document.getElementById('current-q-num').innerText=currentIndex+1;

        const container=document.getElementById('options-container');
        container.innerHTML='';

        [{num:1,text:q.option1},{num:2,text:q.option2},{num:3,text:q.option3},{num:4,text:q.option4}]
        .forEach(opt=>{
            const sel=userAnswers[q.id]===opt.num;
            container.innerHTML+=`<div onclick="selectOption(${q.id},${opt.num})" class="group p-4 border-2 rounded-xl cursor-pointer mb-3 transition-all ${sel?'border-green-500 bg-green-50':'border-gray-200 hover:border-green-500 hover:bg-green-50'}">
                <span class="font-bold mr-2 text-gray-700">${opt.num}.</span>
                <span class="text-gray-800 font-medium">${opt.text}</span>
            </div>`;
        });

        document.getElementById('btn-prev').classList.toggle('hidden',currentIndex===0);
        document.getElementById('btn-next').classList.toggle('hidden',currentIndex===questions.length-1);
        document.getElementById('btn-finish').classList.toggle('hidden',currentIndex!==questions.length-1);

        startTimer();
    }

    function selectOption(qId,opNum){userAnswers[qId]=opNum; renderQuestion();}
    function nextQuestion(){if(currentIndex<questions.length-1){currentIndex++; renderQuestion();}}
    function prevQuestion(){if(currentIndex>0){currentIndex--; renderQuestion();}}

    function startTimer(){
        clearInterval(timerInterval);
        let seconds=10;
        const timerEl=document.getElementById('timer');
        timerEl.innerText="00:"+seconds.toString().padStart(2,'0');
        timerEl.classList.remove('text-red-500');

        timerInterval=setInterval(()=>{
            seconds--;
            timerEl.innerText="00:"+seconds.toString().padStart(2,'0');
            if(seconds<=3) timerEl.classList.add('text-red-500 animate-pulse');
            if(seconds<=0){
                clearInterval(timerInterval);
                if(currentIndex<questions.length-1) nextQuestion(); 
                else alert("Temps écoulé !");
            }
        },1000);
    }

    function submitQuiz(){
        if(!confirm('Voulez-vous vraiment terminer le quiz ?')) return;
        clearInterval(timerInterval);
        fetch('quiz_ajax_handler.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({action:'submit_quiz', quiz_id:quizId, answers:userAnswers})
        }).then(r=>r.json())
        .then(data=>{
            if(data.success) window.location.href='quiz_review.php?result_id='+data.result_id;
            else alert("Erreur: "+data.message);
        }).catch(console.error);
    }
</script>
