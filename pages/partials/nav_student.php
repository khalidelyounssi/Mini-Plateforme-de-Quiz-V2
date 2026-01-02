

<nav class="bg-white shadow-lg fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-green-600">Qodex<span class="text-gray-800">Student</span></span>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="../student/dashboard.php" class="border-green-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Catégories
                    </a>
                    <a href="../student/my_results.php" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Mes Résultats
                    </a>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                
                <span class="text-gray-700 font-medium">
                    Bonjour, 
                    <span class="font-bold text-green-600">
                        <?php echo htmlspecialchars($_SESSION['user_nom'] ?? 'Étudiant'); ?>
                    </span>
                </span>

                <a href="../auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>

            </div>
        </div>
    </div>
</nav>
<div class="h-16"></div>