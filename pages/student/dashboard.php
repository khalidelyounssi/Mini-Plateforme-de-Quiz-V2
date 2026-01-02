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

<div class="bg-gray-50 min-h-screen">
    
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold mb-4">Espace Étudiant</h1>
            <p class="text-xl text-green-100">Bienvenue sur votre plateforme d'apprentissage.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-3xl font-bold text-gray-900">Catégories Disponibles</h2>
            
            <a href="my_results.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
    <i class="fas fa-chart-line mr-2"></i>
    Voir mes résultats
</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (count($categorys)>0): ?>
            <?php foreach( $categorys as $cat ): ?>


            <div onclick="window.location.href='quizzes.php?category_id=<?php echo $cat['id']; ?>'" class="bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 overflow-hidden group cursor-pointer border border-gray-100 transform hover:-translate-y-1">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white relative overflow-hidden">
                    <i class="fas fa-code text-5xl absolute -right-4 -bottom-4 opacity-20 transform rotate-12"></i>
                    <i class="fas fa-code text-3xl mb-3 relative z-10"></i>
                    <h3 class="text-xl font-bold relative z-10"><?php echo $cat['nom']?></h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4 text-sm"><?php echo $cat['description'] ?></p>
                    <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-100">
                        <span class="text-gray-500"><i class="fas fa-layer-group mr-2"></i><?php echo $cat['quiz_count']; ?></span>
                        <span class="text-blue-600 font-bold group-hover:translate-x-2 transition-transform">Accéder →</span>
                    </div>
                </div>
            </div>   
            <?php endforeach ;?> 

            <?php else: ?>
                <div class="col-span-full text-center py-12 bg-white rounded-lg shadow-sm">
                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune catégorie disponible pour le moment.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>