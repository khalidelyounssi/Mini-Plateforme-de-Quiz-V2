<?php
session_start();
require_once '../partials/header.php';
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Question.php';

if (!isset($_GET['result_id'])) {
    header('Location: dashboard.php');
    exit();
}
$resultId = $_GET['result_id'];
$db = Database::getInstance();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM results WHERE id = ?");
$stmt->execute([$resultId]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo "Résultat introuvable.";
    exit();
}

$userAnswers = json_decode($result['user_answers_json'], true);
$quizId = $result['quiz_id'];

$qObj = new Question();
$questions = $qObj->getAllByQuiz($quizId);

$quizObj = new Quiz();
$quizInfo = $quizObj->getById($quizId);
?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4">
        
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 text-center border-t-4 <?php echo ($result['score'] >= $result['total_questions']/2) ? 'border-green-500' : 'border-red-500'; ?>">
            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($quizInfo['titre']); ?></h1>
            <p class="text-gray-600">Résultat final</p>
            <div class="text-5xl font-bold mt-4 mb-2 <?php echo ($result['score'] >= $result['total_questions']/2) ? 'text-green-600' : 'text-red-600'; ?>">
                <?php echo $result['score']; ?> / <?php echo $result['total_questions']; ?>
            </div>
            <a href="dashboard.php" class="text-indigo-600 hover:underline mt-4 inline-block">Retour au Dashboard</a>
        </div>

        <div class="space-y-6">
            <?php foreach($questions as $index => $q): 
                $userChoice = $userAnswers[$q['id']] ?? null;
                $correct = $q['correct_option'];
                
                $isCorrect = ($userChoice == $correct);
                $cardBorder = $isCorrect ? 'border-green-200' : 'border-red-200';
            ?>
                
                <div class="bg-white rounded-lg shadow p-6 border-2 <?php echo $cardBorder; ?>">
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-start">
                        <span class="bg-gray-100 text-gray-600 text-sm py-1 px-2 rounded mr-3 mt-1">Q<?php echo $index + 1; ?></span>
                        <?php echo htmlspecialchars($q['question']); ?>
                    </h3>

                    <div class="space-y-2">
                        <?php for($i=1; $i<=4; $i++): 
                            $optionText = $q['option'.$i];
                            $style = "border-gray-100 bg-white text-gray-600"; 
                            $icon = "";

                            if ($i == $correct) {
                                $style = "border-green-500 bg-green-50 text-green-800 font-bold";
                                $icon = '<i class="fas fa-check float-right mt-1"></i>';
                            }

                            if ($i == $userChoice && $i != $correct) {
                                $style = "border-red-500 bg-red-50 text-red-800";
                                $icon = '<i class="fas fa-times float-right mt-1"></i> (Votre choix)';
                            }
                        ?>
                            <div class="p-3 border rounded <?php echo $style; ?>">
                                <?php echo $i . '. ' . htmlspecialchars($optionText); ?>
                                <?php echo $icon; ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <?php if(!$isCorrect): ?>
                        <div class="mt-4 text-sm text-red-600">
                            <i class="fas fa-info-circle"></i> Vous avez répondu faux. La bonne réponse était l'option <?php echo $correct; ?>.
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
        
        

    </div>
</div>

<?php require_once '../partials/footer.php'; ?>