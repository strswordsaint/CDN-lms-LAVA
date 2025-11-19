<?php include 'app/views/layouts/header.php'; ?>

<script src="https://unpkg.com/knockout@3.5.1/build/output/knockout-latest.js"></script>
<script src="https://unpkg.com/survey-core@1.9.101/survey.core.min.js"></script>
<script src="https://unpkg.com/survey-knockout-ui@1.9.101/survey-knockout-ui.min.js"></script>
<script src="https://unpkg.com/survey-creator-core@1.9.101/survey-creator-core.min.js"></script>
<script src="https://unpkg.com/survey-creator-knockout@1.9.101/survey-creator-knockout.min.js"></script>
<link href="https://unpkg.com/survey-core@1.9.101/defaultV2.min.css" type="text/css" rel="stylesheet">
<link href="https://unpkg.com/survey-creator-core@1.9.101/survey-creator-core.min.css" type="text/css" rel="stylesheet">

<style>
    /* Fix creator height */
    #surveyCreatorContainer { height: 80vh; width: 100%; }
    .svc-creator__banner { display: none !important; } /* Hide trial banner if possible */
</style>

<div class="container mx-auto px-4 py-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Quiz Builder</h1>
        <a href="<?php echo site_url('/courses/show/' . $course_id); ?>" class="btn btn-secondary">Cancel</a>
    </div>

    <div class="card p-4 mb-4 grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-bold">Quiz Title</label>
            <input type="text" id="quiz_title" class="form-input" placeholder="E.g., Chapter 1 Quiz">
        </div>
        <div>
            <label class="block text-sm font-bold">Due Date</label>
            <input type="datetime-local" id="due_date" class="form-input">
        </div>
        <div>
            <label class="block text-sm font-bold">Total Points</label>
            <input type="number" id="points" class="form-input" value="100">
        </div>
    </div>

    <div id="surveyCreatorContainer"></div>
</div>

<script>
    // 1. Configure Creator options
    const options = {
        showLogicTab: true,
        isAutoSave: false
    };
    const creator = new SurveyCreator.SurveyCreator(options);
    
    // 2. Render
    creator.render("surveyCreatorContainer");

    // 3. Hook into the "Save" button of the creator
    creator.saveSurveyFunc = function (saveNo, callback) {
        const title = document.getElementById('quiz_title').value;
        const dueDate = document.getElementById('due_date').value;
        const points = document.getElementById('points').value;
        
        if(!title || !dueDate) {
            alert("Please fill in the Title and Due Date at the top.");
            callback(saveNo, false);
            return;
        }

        // Send to Controller
        $.ajax({
            url: "<?php echo site_url('/quizzes/store'); ?>",
            type: "POST",
            data: {
                course_id: <?php echo $course_id; ?>,
                title: title,
                due_date: dueDate,
                points: points,
                quiz_data: creator.text, // The JSON string
                csrf_test_name: "<?php echo lava_instance()->security->get_csrf_hash(); ?>" // CSRF Token
            },
            success: function(response) {
                const res = JSON.parse(response);
                if(res.status === 'success') {
                    alert("Quiz Saved!");
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