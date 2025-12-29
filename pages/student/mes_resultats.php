<?php
session_start();
// Require Classes...
?>

<?php require_once '../partials/header.php'; ?>
<?php require_once '../partials/nav_student.php'; ?>

<div class="bg-gray-50 min-h-screen">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="dashboard.php" class="text-white hover:text-green-100 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
            </a>
            <h1 class="text-4xl font-bold mb-2">Mes Résultats</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Les Bases de HTML5</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-green-600">18/20</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04 Déc 2024</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>