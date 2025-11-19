<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<?php include 'app/views/layouts/header.php'; ?>

<script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>
<script src="https://unpkg.com/survey-core@1.9.101/survey.core.min.js"></script>
<script src="https://unpkg.com/survey-knockout-ui@1.9.101/survey-knockout-ui.min.js"></script>
<script src="https://unpkg.com/survey-creator-core@1.9.101/survey-creator-core.min.js"></script>
<script src="https://unpkg.com/survey-creator-knockout@1.9.101/survey-creator-knockout.min.js"></script>
<link href="https://unpkg.com/survey-core@1.9.101/defaultV2.min.css" type="text/css" rel="stylesheet">
<link href="https://unpkg.com/survey-creator-core@1.9.101/survey-creator-core.min.css" type="text/css" rel="stylesheet">

<style>
    #surveyCreatorContainer { height: 80vh; width: 100%; }
    .svc-creator__banner { display: none !important; }
</style>

<div class="container mx-auto px-4 py-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Edit Quiz</h1>
        <a href="<?php echo site_url('/courses/show/' . $course_id); ?>" class="btn btn-secondary">Cancel</a>
    </div>

    <div class="card p-4 mb-4 grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-bold">Quiz Title</label>
            <input type="text" id="quiz_title" class="form-input" value="<?php echo htmlspecialchars($quiz['title']); ?>">
        </div>
        <div>
            <label class="block text-sm font-bold">Due Date</label>
            <input type="datetime-local" id="due_date" class="form-input" value="<?php echo date('Y-m-d\TH:i', strtotime($quiz['due_date'])); ?>">
        </div>
        <div>
            <label class="block text-sm font-bold">Total Points</label>
            <input type="number" id="points" class="form-input" value="<?php echo htmlspecialchars($quiz['points']); ?>">
        </div>
    </div>

    <div id="surveyCreatorContainer"></div>
</div>

<script>
    const options = { showLogicTab: true, isAutoSave: false };
    const creator = new SurveyCreator.SurveyCreator(options);
    
    // --- LOAD EXISTING DATA ---
    // We use strict JSON.stringify from PHP to ensure valid JS object
    creator.text = JSON.stringify(<?php echo $quiz['quiz_data']; ?>);
    
    creator.render("surveyCreatorContainer");

    creator.saveSurveyFunc = function (saveNo, callback) {
        $.ajax({
            url: "<?php echo site_url('/quizzes/update/' . $quiz['assignment_id']); ?>",
            type: "POST",
            data: {
                title: document.getElementById('quiz_title').value,
                due_date: document.getElementById('due_date').value,
                points: document.getElementById('points').value,
                quiz_data: creator.text, 
                csrf_test_name: "<?php echo lava_instance()->security->get_csrf_hash(); ?>"
            },
            success: function(response) {
                const res = JSON.parse(response);
                if(res.status === 'success') {
                    alert("Quiz Updated!");
                    window.location.href = "<?php echo site_url('/courses/show/' . $course_id); ?>";
                } else {
                    alert("Error: " + res.message);
                }
                callback(saveNo, true);
            }
        });
    };
</script>
<?php include 'app/views/layouts/footer.php'; ?>