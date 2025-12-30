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

<div class="bg-gray-50 min-h-screen">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="dashboard.php" class="text-white hover:text-green-100 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux catégories
            </a>
            <h1 class="text-4xl font-bold mb-2"><?php echo $category['nom'] ?></h1> <p class="text-xl text-green-100"><?php echo $category['description'] ?></p>
        </div>
    </div>
     
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <?php foreach($allQuiz as $quiz): ?>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition flex flex-col justify-between h-full">
    
    <div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">
            <?php echo htmlspecialchars($quiz['titre']); ?>
        </h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            <?php echo htmlspecialchars($quiz['description']); ?>
        </p>
        
        <div class="flex items-center text-xs text-gray-500 mb-4">
            <i class="fas fa-question-circle mr-1"></i>
            <span class="font-semibold mr-1"><?= $quiz['question_count']; ?></span> Questions
        </div>
        
    </div>
    
    <a href="quiz_take.php?quiz_id=<?= $quiz['id']; ?>" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition mt-4">
        Commencer le Quiz
    </a>
    
</div>

    <?php endforeach;

     ?>
</div>

<?php require_once '../partials/footer.php'; ?>