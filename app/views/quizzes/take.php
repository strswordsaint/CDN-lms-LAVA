<?php include 'app/views/layouts/header.php'; ?>

<script src="https://unpkg.com/jquery/dist/jquery.min.js"></script>
<script src="https://unpkg.com/survey-jquery@1.9.101/survey.jquery.min.js"></script>
<link href="https://unpkg.com/survey-core@1.9.101/defaultV2.min.css" type="text/css" rel="stylesheet">

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <a href="<?php echo site_url('/my-courses/' . $quiz['course_id']); ?>" class="text-sm text-blue-600 mb-4 inline-block">&larr; Back to Course</a>
    
    <div class="card p-6">
        <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($quiz['title']); ?></h1>
        <p class="text-sm text-gray-500 mb-6">Points: <?php echo $quiz['points']; ?> | Due: <?php echo date('M d, g:i A', strtotime($quiz['due_date'])); ?></p>
        
        <div id="surveyContainer"></div>
    </div>
</div>

<script>
    // 1. Load the Quiz Structure from DB
    const surveyJson = <?php echo $quiz['quiz_data']; ?>;
    
    // 2. Initialize
    const survey = new Survey.Model(surveyJson);
    
    // 3. Handle Completion
    survey.onComplete.add(function (sender) {
        // Calculate score (SurveyJS can do simple counting, or we just count correct answers)
        // For now, we will trust the client-side calculation for simplicity, 
        // but for a real exam, you'd grade on the server.
        
        let totalCorrect = 0;
        let totalQuestions = 0;
        
        // Simple grading loop (works if 'correctAnswer' is set in JSON)
        const data = sender.data;
        sender.getAllQuestions().forEach(q => {
            if(q.correctAnswer !== undefined) {
                totalQuestions++;
                if(q.correctAnswer == data[q.name]) {
                    totalCorrect++;
                }
            }
        });
        
        // Calculate scaled score based on assignment points
        const maxPoints = <?php echo $quiz['points']; ?>;
        let finalScore = 0;
        if(totalQuestions > 0) {
            finalScore = (totalCorrect / totalQuestions) * maxPoints;
        } else {
            // If no correct answers defined, give full points for participation? 
            // Or 0. Let's assume 0.
            finalScore = 0; 
        }
        
        // Send results
        $.ajax({
            url: "<?php echo site_url('/quizzes/submit/' . $quiz['assignment_id']); ?>",
            type: "POST",
            data: {
                results: JSON.stringify(sender.data),
                score: Math.round(finalScore), // Round to nearest int
                max_points: maxPoints,
                csrf_test_name: "<?php echo lava_instance()->security->get_csrf_hash(); ?>"
            },
            success: function(res) {
                // Redirect after slight delay
                setTimeout(() => {
                     window.location.href = "<?php echo site_url('/my-courses/' . $quiz['course_id']); ?>";
                }, 1000);
            }
        });
    });

    // Render
    $("#surveyContainer").Survey({ model: survey });
</script>
<?php include 'app/views/layouts/footer.php'; ?>