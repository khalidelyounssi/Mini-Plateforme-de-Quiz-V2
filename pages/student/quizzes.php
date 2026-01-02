<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/User.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';


$db = Database::getInstance();
$conn = $db->getConnection();
$categoryId = $_GET['category_id'];

$monCatigory = new Category();
$category = $monCatigory->getById($categoryId) ;





$monQuiz = new Quiz();
$allQuiz = $monQuiz->getAllCategory($categoryId) ;








?>

<?php require_once '../partials/header.php'; ?>
<?php require_once '../partials/nav_student.php'; ?>

<div class="bg-gray-100 min-h-screen">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-800 to-teal-500 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="dashboard.php" class="text-white hover:text-teal-200 mb-4 inline-block transition">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux catégories
            </a>
            <h1 class="text-4xl font-extrabold mb-2 animate-fadeInDown"><?php echo htmlspecialchars($category['nom']); ?></h1>
            <p class="text-xl text-teal-100 animate-fadeIn delay-200"><?php echo htmlspecialchars($category['description']); ?></p>
        </div>
    </div>

    <!-- Quizzes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <?php if(count($allQuiz) > 0): ?>
            <?php foreach($allQuiz as $quiz): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-2xl transform hover:-translate-y-2 hover:scale-105 transition duration-500 flex flex-col justify-between h-full group">

                <!-- Quiz Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition duration-300">
                        <?php echo htmlspecialchars($quiz['titre']); ?>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?php echo htmlspecialchars($quiz['description']); ?>
                    </p>

                    <div class="flex items-center text-xs text-gray-500 mb-4">
                        <i class="fas fa-question-circle mr-1"></i>
                        <span class="font-semibold mr-1"><?php echo $quiz['question_count']; ?></span> Questions
                    </div>
                </div>

                <!-- Start Quiz Button -->
                <a href="quiz_take.php?quiz_id=<?php echo $quiz['id']; ?>" 
                    class="block w-full text-center px-4 py-2 rounded-lg shadow-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition transform hover:scale-105">
                    Commencer le Quiz
                </a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-16 bg-white rounded-2xl shadow-md animate-fadeIn">
                <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Aucun quiz disponible pour cette catégorie.</p>
            </div>
        <?php endif; ?>

    </div>
</div>
