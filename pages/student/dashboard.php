<?php

session_start();
require_once '../partials/header.php'; 
require_once '../partials/nav_student.php'; 
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/User.php';
require_once '../../classes/Category.php';




$db = Database::getInstance();
$conn = $db->getConnection();

$monCatigory = new Category();
$categorys= $monCatigory->getAllC();







?>
<style>
    @keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
  animation: fadeIn 1s ease forwards;
}

.animate-fadeInDown {
  animation: fadeInDown 1s ease forwards;
}

.animate-pulse {
  animation: pulse 2s infinite;
}

</style>

<div class="bg-gray-100 min-h-screen">

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-800 to-teal-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 class="text-5xl font-extrabold mb-4 animate-fadeInDown">Espace Étudiant</h1>
            <p class="text-xl text-teal-100 animate-fadeIn delay-200">Bienvenue sur votre plateforme de quiz.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <h2 class="text-3xl font-bold text-gray-900 animate-fadeIn">Catégories Disponibles</h2>
            
            <a href="my_results.php" class="inline-flex items-center px-5 py-3 border border-transparent shadow-lg text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 hover:scale-105 transition-transform duration-300 animate-fadeIn delay-300">
                <i class="fas fa-chart-line mr-2"></i>
                Voir mes résultats
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if (count($categorys) > 0): ?>
                <?php foreach($categorys as $cat): ?>
                <div onclick="window.location.href='quizzes.php?category_id=<?php echo $cat['id']; ?>'" 
                    class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 hover:scale-105 cursor-pointer border border-gray-200 overflow-hidden group">

                    <!-- Card Header -->
                    <div class="bg-gradient-to-br from-blue-500 to-teal-400 p-8 text-white relative overflow-hidden">
                        <i class="fas fa-brain text-6xl absolute -right-6 -bottom-6 opacity-15 transform rotate-12"></i>
                        <i class="fas fa-brain text-3xl mb-3 relative z-10 animate-pulse"></i>
                        <h3 class="text-2xl font-bold relative z-10"><?php echo $cat['nom'] ?></h3>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <p class="text-gray-700 mb-4 text-sm line-clamp-3"><?php echo $cat['description'] ?></p>
                        <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-200">
                            <span class="text-gray-500"><i class="fas fa-layer-group mr-2"></i><?php echo $cat['quiz_count']; ?> Quiz</span>
                            <span class="text-teal-600 font-bold group-hover:translate-x-2 transition-transform duration-300">Accéder →</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16 bg-white rounded-2xl shadow-sm animate-fadeIn">
                    <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune catégorie disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>

