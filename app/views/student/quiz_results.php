<?php include 'app/views/layouts/header.php'; ?>

<script src="https://unpkg.com/jquery/dist/jquery.min.js"></script>
<script src="https://unpkg.com/survey-jquery@1.9.101/survey.jquery.min.js"></script>
<link href="https://unpkg.com/survey-core@1.9.101/defaultV2.min.css" type="text/css" rel="stylesheet">

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <a href="<?php echo site_url('/my-courses/' . $quiz['course_id']); ?>" class="text-sm text-blue-600 mb-4 inline-block">&larr; Back to Course</a>
    
    <div class="card p-6 mb-6 border-l-4 border-green-500">
        <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($quiz['title']); ?></h1>
        <div class="flex items-center justify-between mt-4">
            <div>
                <p class="text-sm text-gray-500">Completed on: <?php echo date('M d, Y @ g:i A', strtotime($submission['submitted_at'])); ?></p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-green-600"><?php echo floatval($submission['grade']); ?> / <?php echo floatval($quiz['points']); ?></p>
                <p class="text-sm font-semibold text-gray-500">Final Score</p>
            </div>
        </div>
    </div>
    
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Quiz Review</h3>
        <div id="surveyContainer"></div>
    </div>
</div>

<script>
    // 1. Load the Quiz Structure
    const surveyJson = <?php echo $quiz['quiz_data']; ?>;
    
    // 2. Initialize Survey
    const survey = new Survey.Model(surveyJson);
    
    // 3. Load Student Answers
    const userResults = <?php echo $submission['quiz_result_json']; ?>;
    survey.data = userResults;

    // 4. Set to "Display" mode (Read Only)
    survey.mode = 'display';
    
    // 5. Render
    $("#surveyContainer").Survey({ model: survey });
</script>
<?php include 'app/views/layouts/footer.php'; ?>