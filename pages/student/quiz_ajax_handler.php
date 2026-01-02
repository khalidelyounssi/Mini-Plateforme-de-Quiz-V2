<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Question.php';
require_once '../../classes/Result.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        $action = $_POST['action'] ?? ($input['action'] ?? '');
        
        if ($action === 'get_questions') {
            $quizId = $_POST['quiz_id'] ?? 0;
            
            if (!$quizId) {
                throw new Exception("Quiz ID manquant");
            }

            $qObj = new Question();
            $questions = $qObj->getAllByQuiz($quizId);
            
            $quizObj = new Quiz();
            $quizInfo = $quizObj->getById($quizId);
            
            echo json_encode([
                'success' => true, 
                'quiz_info' => $quizInfo,
                'questions' => $questions
            ]);
            exit;
        }

        if ($action === 'submit_quiz') {
            $quizId = $input['quiz_id'] ?? ($_POST['quiz_id'] ?? 0);
            $userAnswers = $input['answers'] ?? (json_decode($_POST['answers'] ?? '[]', true));
            
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Session expirée, veuillez vous reconnecter.']);
                exit;
            }
            $userId = $_SESSION['user_id'];

            $resObj = new Result();
            if ($resObj->checkAttempt($userId, $quizId)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Tentative rejetée : Vous avez déjà passé ce quiz !'
                ]);
                exit;
            }

            $qObj = new Question();
            $allQuestions = $qObj->getAllByQuiz($quizId);
            $score = 0;
            $totalQuestions = count($allQuestions);
            
            foreach ($allQuestions as $q) {
                if (isset($userAnswers[$q['id']]) && $userAnswers[$q['id']] == $q['correct_option']) {
                    $score++;
                }
            }

            $answersJson = json_encode($userAnswers);

            $db = Database::getInstance();
            $sql = "INSERT INTO results (quiz_id, etudiant_id, score, total_questions, user_answers_json, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            
            $db->query($sql, [$quizId, $userId, $score, $totalQuestions, $answersJson]);

            $lastResultId = $db->getConnection()->lastInsertId();

            echo json_encode([
                'success' => true, 
                'score' => $score,
                'result_id' => $lastResultId
            ]);
            exit;
        }

        throw new Exception("Action non reconnue: " . $action);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
?>