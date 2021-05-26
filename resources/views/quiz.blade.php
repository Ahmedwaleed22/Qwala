<?php
// include 'includes/init.php';
// include $tpl . 'connect.php';
// ?>
// <link rel="stylesheet" href="<?php echo $css . 'quiz.css' ?>" />
// <?php
// if (isset($_GET['quiz'])) {

// $quizID = filter_var($_GET['quiz'], FILTER_SANITIZE_NUMBER_INT);

// $stmt = $con->prepare("SELECT quizzes.*, quizQuestions.Question as Question, quizQuestions.ID as QuestionID FROM quizzes INNER JOIN quizQuestions ON quizzes.ID = quizQuestions.QuizID WHERE quizzes.CourseID = ?");
// $stmt->execute(array($quizID));
// $rows = $stmt->fetchAll();
?>
<form id="pointsform" action="/scored/" method="post">
    <input id="totalpoints" type="hidden" name="totalpoints" value="0">
</form>
<div id="app">
    <div class="container questionArea" id="questionArea">
        <div class="question">
            <p class="question-text">{{ getQuestion() }}</p>
        </div>
        <div class="answers" id="answers">
            <div class="answer" v-for="(answer, index) in answers" @click.prevent="submitAnswer($event)">
                <div v-if="showAnswers">     
                    <div class="content" v-if="answer.Is_true == 1" style="background: green; color: #fff">
                        <span class="number">{{ index + 1 }}</span>
                        <span class="answer-text">{{ answer.Answer }}</span>
                    </div>
                    <div class="content" v-else style="background: red; color: #fff">
                        <span class="number">{{ index + 1 }}</span>
                        <span class="answer-text">{{ answer.Answer }}</span>
                    </div>
                </div>
                <div class="content" v-else>
                    <span class="number">{{ index + 1 }}</span>
                    <span class="answer-text">{{ answer.Answer }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="bottomBar">
        <span class="left">Question <span class="qesnumber">{{ questionNumber + 1 }}</span> Of {{ question.length }}</span>
        <span class="right">
            <button v-if="answered" @click.prevent="nextQuestion()">NEXT ></button>
            <button v-else disabled @click.prevent="nextQuestion()">NEXT ></button>
        </span>
    </div>
</div>
<script>
var app = new Vue({
    el: "#app",
    created: function () {
      this.loadData();
    },
    data: {
        questionNumber: 0,
        question: <?php echo json_encode($rows); ?>,
        answers: [],
        answered: false,
        showAnswers: false,
        trueAnswers: 0,
        selectedAnswer: null,
        answersLength: 0,
    },
    methods: {
        loadData: function (viewerUserId, posterUserId) {
            const that = this;
            let QuestionID = this.question[this.questionNumber].QuestionID
            $.ajax({
              contentType: "application/json",
              dataType: "json",
              url: `/todolist/quizanswers.php?ID=${QuestionID}`,
              method: "get",
              success: response => {
                console.log(response.length);
                if (response.length > 0) {
                    this.answers = JSON.parse(JSON.stringify(response))
                }
              },
              error: err => alert('Error')
            });
        },
        getQuestion: function () {
            return this.question[this.questionNumber].Question;
        },
        submitAnswer: function (event) {
            var userAnswer = event.target.textContent.split(" ");
            if (userAnswer.length > 2) {
                userAnswer.shift();
                var userAnswer = userAnswer.join(' ');
            } else {
                var userAnswer = userAnswer[1];
            }

            let answers = document.querySelectorAll(".answer .content");

            answers.forEach(answer => {
                answer.style.background = "transparent";
            });
            event.target.style.background = "rgb(206 206 206)";

            this.selectedAnswer = userAnswer;
            this.answered = true;
        },
        nextQuestion: function() {
            this.showAnswers = true;

            this.answers.forEach(answer => {
                if (answer.Is_true == 1) {
                    if (this.selectedAnswer == answer.Answer) this.trueAnswers += 1;
                }
            });

            if (this.question.length <= (this.questionNumber + 1)) {
                window.open(`/todolist/scored.php?score=${this.trueAnswers}&total=${this.question.length}`, '_self');
            } else {
                let countdown = setTimeout(() => {
                    this.showAnswers = false;
                    this.questionNumber += 1;
                }, 1000);
            }
        },
    },
    watch: {
        questionNumber: function(val) {
            this.loadData();
        },
    }
});
</script>
<?php
} else { ?>
<div class="container questionArea" id="container">
    <div class="question">
        <p class="question-text">
            What Quiz Do You Want?
        </p>
    </div>
    <div class="answers">
        <?php
        $stmt = $con->query("SELECT quizzes.*, courses.name as name, courses.ID as ID from quizzes INNER JOIN courses ON quizzes.CourseID = courses.ID");
        $rows = $stmt->fetchAll();

        foreach($rows as $index=>$row) {
        ?>
        <div class="answer" onclick="window.open('?quiz=<?php echo $row['ID'] ?>', '_self')">
            <div class="content">
                <span class="number"><?php echo $index += 1 ?></span>
                <span class="answer-text"><?php echo $row['name'] ?></span>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
}
include $tpl . 'footer.php';
?>