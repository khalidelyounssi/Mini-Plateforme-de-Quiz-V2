<?php





?>

<?php require_once '../partials/header.php'; ?>
<?php require_once '../partials/nav_student.php'; ?>

<div class="bg-gray-50 min-h-screen">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="dashboard.php" class="text-white hover:text-green-100 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Retour aux catégories
            </a>
            <h1 class="text-4xl font-bold mb-2">HTML/CSS</h1> <p class="text-xl text-green-100">Sélectionnez un quiz pour commencer</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Les Bases de HTML5</h3>
                <p class="text-gray-600 text-sm mb-4">Testez vos connaissances sur les balises...</p>
                <a href="quiz_take.php?quiz_id=5" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    Commencer le Quiz
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>