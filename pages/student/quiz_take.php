<?php 
session_start();
require_once '../partials/header.php'; 
require_once '../partials/nav_student.php'; 

if (!isset($_GET['quiz_id'])) {
    header('Location: dashboard.php');
    exit();
}
$quizId = $_GET['quiz_id'];
?>

<div class="bg-gray-50 min-h-screen">

    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2" id="quiz-title">Chargement du quiz...</h1>
                    <p class="text-green-100 text-lg">
                        Question <span class="font-bold" id="current-q-num">-</span> sur <span id="total-q-num">-</span>
                    </p>
                </div>
                <div class="text-right bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
                    <div class="text-xs text-green-100 uppercase tracking-wider mb-1">Temps restant</div>
                    <div class="text-3xl font-mono font-bold" id="timer">00:00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 border border-gray-100">
            
            <h3 id="question-text" class="text-xl md:text-2xl font-bold text-gray-900 mb-8 leading-relaxed">
                Veuillez patienter, connexion au serveur...
            </h3>

            <div id="options-container" class="space-y-4"></div>

            <div class="flex justify-between items-center mt-10 pt-6 border-t border-gray-100">
                <button id="btn-prev" onclick="prevQuestion()" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-600 font-medium hover:bg-gray-50 transition flex items-center hidden">
                    <i class="fas fa-arrow-left mr-2"></i> Précédent
                </button>

                <button id="btn-next" onclick="nextQuestion()" class="px-8 py-3 rounded-lg bg-green-600 text-white font-bold hover:bg-green-700 shadow-md transition flex items-center transform hover:-translate-y-0.5 hidden">
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
    let questions = [];    
    let currentIndex = 0; 
    let userAnswers = {}; 
    let timerInterval;   

    document.addEventListener('DOMContentLoaded', () => {
        
        const formData = new FormData();
        formData.append('action', 'get_questions');
        formData.append('quiz_id', quizId);

        fetch('quiz_ajax_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP " + response.status + " (Vérifiez le fichier handler)");
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                questions = data.questions;
                
                if(data.quiz_info) {
                    document.getElementById('quiz-title').innerText = data.quiz_info.titre;
                }
                document.getElementById('total-q-num').innerText = questions.length;
                
                if(questions.length > 0) {
                    renderQuestion(); 
                } else {
                    document.getElementById('question-text').innerText = "Ce quiz ne contient aucune question.";
                }
            } else {
                alert("Erreur Serveur: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('question-text').innerText = "Erreur de connexion : " + error.message;
            document.getElementById('question-text').classList.add('text-red-600');
        });
    });

    function renderQuestion() {
        const q = questions[currentIndex];
        
        document.getElementById('question-text').innerText = q.question;
        document.getElementById('current-q-num').innerText = currentIndex + 1;

        const container = document.getElementById('options-container');
        container.innerHTML = '';

        const options = [
            { num: 1, text: q.option1 }, { num: 2, text: q.option2 },
            { num: 3, text: q.option3 }, { num: 4, text: q.option4 }
        ];

        options.forEach(opt => {
            const isSelected = userAnswers[q.id] === opt.num;
            
            let cssClass = isSelected 
                ? 'border-green-500 bg-green-50' 
                : 'border-gray-200 hover:border-green-500 hover:bg-green-50';
            
            const html = `
            <div onclick="selectOption(${q.id}, ${opt.num})" class="group p-4 border-2 ${cssClass} rounded-xl cursor-pointer mb-3 transition">
                <span class="font-bold mr-2 text-gray-700">${opt.num}.</span>
                <span class="text-gray-800 font-medium">${opt.text}</span>
            </div>`;
            
            container.innerHTML += html;
        });

        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        const btnFinish = document.getElementById('btn-finish');

        if (currentIndex === 0) btnPrev.classList.add('hidden');
        else btnPrev.classList.remove('hidden');

        if (currentIndex === questions.length - 1) {
            btnNext.classList.add('hidden');
            btnFinish.classList.remove('hidden');
        } else {
            btnNext.classList.remove('hidden');
            btnFinish.classList.add('hidden');
        }

        startTimer();
    }

    function selectOption(qId, opNum) {
        userAnswers[qId] = opNum;
        renderQuestion(); 
    }

    function nextQuestion() {
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            renderQuestion();
        }
    }

    function prevQuestion() {
        if (currentIndex > 0) {
            currentIndex--;
            renderQuestion();
        }
    }

    function startTimer() {
        clearInterval(timerInterval);
        let seconds = 10;
        
        document.getElementById('timer').innerText = "00:" + seconds;
        document.getElementById('timer').classList.remove('text-red-500');

        timerInterval = setInterval(() => {
            seconds--;
            document.getElementById('timer').innerText = "00:" + seconds.toString().padStart(2, '0');

            if (seconds <= 3) {
                document.getElementById('timer').classList.add('text-red-500');
            }

            if (seconds <= 0) {
                clearInterval(timerInterval);
                if (currentIndex < questions.length - 1) {
                    nextQuestion();
                } else {
                    alert("Temps écoulé !");
                }
            }
        }, 1000);
    }

    function submitQuiz() {
        if(!confirm('Voulez-vous vraiment terminer le quiz ?')) return;
        
        clearInterval(timerInterval);

        const payload = {
            action: 'submit_quiz',
            quiz_id: quizId,
            answers: userAnswers
        };

        fetch('quiz_ajax_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'quiz_review.php?result_id=' + data.result_id; 
            } else {
                alert("Erreur: " + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

<?php require_once '../partials/footer.php'; ?>