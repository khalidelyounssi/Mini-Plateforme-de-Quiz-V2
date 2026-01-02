<?php
/**
 * Page: Résultats des Étudiants
 * Affiche les performances des élèves pour l'enseignant connecté
 */

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Result.php';

Security::requireLogin();

$currentPage = 'resultats'; 
$pageTitle = 'Résultats des Étudiants';

$teacherId = $_SESSION['user_id'];

// Récupérer les données
$resultObj = new Result();
$studentResults = $resultObj->getStudentResultsByTeacher($teacherId);

$totalQuizPasses = count($studentResults);
$totalScore = 0;
$meilleurScore = 0;

foreach ($studentResults as $res) {
    $percentage = ($res['total_questions'] > 0) ? ($res['score'] / $res['total_questions']) * 100 : 0;
    
    $totalScore += $percentage;
    
    if ($percentage > $meilleurScore) {
        $meilleurScore = $percentage;
    }
}

$moyenneGenerale = ($totalQuizPasses > 0) ? $totalScore / $totalQuizPasses : 0;
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_teacher.php'; ?> 

<div class="pt-16 bg-gray-50 min-h-screen">
    
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-users-cog mr-3 text-indigo-600"></i>Résultats des Étudiants
            </h1>
            <p class="mt-2 text-gray-600">Suivi des performances de vos élèves sur vos quiz.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Participations</p>
                        <p class="text-3xl font-bold text-gray-900"><?= $totalQuizPasses ?></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Moyenne Classe</p>
                        <p class="text-3xl font-bold text-gray-900"><?= round($moyenneGenerale, 1) ?>%</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Meilleur Score</p>
                        <p class="text-3xl font-bold text-gray-900"><?= round($meilleurScore, 1) ?>%</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-crown text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Détails par étudiant</h3>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">
                    <?= count($studentResults) ?> enregistrements
                </span>
            </div>

            <?php if (empty($studentResults)): ?>
                <div class="p-12 text-center text-gray-500">
                    <i class="fas fa-folder-open text-5xl mb-4 text-gray-300"></i>
                    <p>Aucun étudiant n'a encore passé vos quiz.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($studentResults as $res): ?>
                                <?php 
                                    // Calculs
                                    $score = $res['score'];
                                    $total = $res['total_questions'];
                                    $percent = ($total > 0) ? round(($score / $total) * 100) : 0;
                                    
                                    // Status Style
                                    if ($percent >= 80) $badge = "bg-green-100 text-green-800";
                                    elseif ($percent >= 50) $badge = "bg-yellow-100 text-yellow-800";
                                    else $badge = "bg-red-100 text-red-800";
                                ?>
                                <tr class="hover:bg-gray-50 transition">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xs">
                                                <?= strtoupper(substr($res['etudiant_nom'], 0, 2)) ?>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($res['etudiant_nom']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($res['etudiant_email']) ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium"><?= htmlspecialchars($res['quiz_titre']) ?></div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold text-gray-900"><?= $score ?> / <?= $total ?></div>
                                        <div class="text-xs text-gray-500"><?= $percent ?>%</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badge ?>">
                                            <?= ($percent >= 50) ? 'Réussi' : 'Échoué' ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($res['created_at'])) ?>
                                        <span class="block text-xs"><?= date('H:i', strtotime($res['created_at'])) ?></span>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>