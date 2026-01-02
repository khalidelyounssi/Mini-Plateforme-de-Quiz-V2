<nav class="bg-white shadow-md fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo and Navigation -->
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <span class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-teal-500">
                        Qodex<span class="text-gray-800 font-semibold">Student</span>
                    </span>
                </div>

                <div class="hidden sm:flex sm:space-x-4">
                    <a href="../student/dashboard.php" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 
                        border-blue-600 text-gray-900 hover:text-blue-700 hover:border-blue-700 transition-all duration-300">
                        Catégories
                    </a>

                    <a href="../student/my_results.php" 
                        class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 
                        border-transparent text-gray-500 hover:text-blue-700 hover:border-blue-500 transition-all duration-300">
                        Mes Résultats
                    </a>
                </div>
            </div>

            <!-- User Info & Logout -->
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 font-medium text-sm">
                    Bonjour, 
                    <span class="font-bold text-teal-600">
                        <?php echo htmlspecialchars($_SESSION['user_nom'] ?? 'Étudiant'); ?>
                    </span>
                </span>

                <a href="../auth/logout.php" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-md transition transform hover:scale-105">
                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                </a>
            </div>

        </div>
    </div>
</nav>

<!-- Spacer to prevent content overlap -->
<div class="h-16"></div>
