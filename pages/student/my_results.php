<?php
session_start();
require_once '../partials/header.php';
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../partials/nav_student.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$db = Database::getInstance();
$conn = $db->getConnection();

$sql = "SELECT r.*, q.titre 
        FROM results r 
        JOIN quiz q ON r.quiz_id = q.id 
        WHERE r.etudiant_id = ? 
        ORDER BY r.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mes Résultats</h1>
                <p class="mt-1 text-sm text-gray-500">Historique de tous les quiz passés.</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <span class="text-gray-500 text-sm font-medium">Total Quiz:</span>
                <span class="text-indigo-600 font-bold text-lg ml-2"><?php echo count($results); ?></span>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            
            <?php if(count($results) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach($results as $res): 
                                $percentage = ($res['total_questions'] > 0) 
                                              ? round(($res['score'] / $res['total_questions']) * 100) 
                                              : 0;
                                
                                $isPass = ($percentage >= 50);
                            ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        <?php echo htmlspecialchars($res['titre']); ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <?php 
                                            $date = $res['created_at'] ?? $res['completed_at'];
                                            echo date('d/m/Y', strtotime($date)); 
                                        ?>
                                        <span class="text-xs text-gray-400 ml-1">
                                            <?php echo date('H:i', strtotime($date)); ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm font-bold text-gray-800">
                                        <?php echo $res['score']; ?> <span class="text-gray-400 text-xs font-normal">/ <?php echo $res['total_questions']; ?></span>
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($isPass): ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check mr-1 mt-0.5"></i> Réussi (<?php echo $percentage; ?>%)
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times mr-1 mt-0.5"></i> Échoué (<?php echo $percentage; ?>%)
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="quiz_review.php?result_id=<?php echo $res['id']; ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-md transition border border-indigo-200">
                                        Voir <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-clipboard-list text-xl"></i>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun résultat</h3>
                    <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore passé de quiz.</p>
                    <div class="mt-6">
                        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Passer un quiz
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>