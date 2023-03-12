<template lang="">
    <div class="container">
        <div class="text-center mt-50" v-if="!stopGame">
            <Question :digit1="currentQuestion.digit1" :digit2="currentQuestion.digit2" :sign="sign" />
            <Timer :time="remainingTime" />
        </div>
        <div class="mt-50 w-50">
            <div class="answers">
                <div v-for="(answer, index) in answers" :key="index" class="answer" :id="`answer-${index}`" v-on:click="guessAnswer(answer, index)" >
                    {{ answer.answer }}
                </div>
            </div>
        </div>
        <Win :show="completed" />
        <Loss :show="failed" />
    </div>
</template>
<script>
import Question from '../components/Question.vue';
import Timer from '../components/Timer.vue';
import Loss from '../components/Loss.vue';
import Win from '../components/Win.vue';
export default {
    components:{
        Loss, Win, Question, Timer,
    },
    data() {
        return {
            questions:[],
            answers:[],
            currentQuestion:{},
            currentQuestionIndex:0,
            sign:'',
            questionTime:0,
            remainingTime:0,
            correctAnswers:[],
            correctIndexes:[],
            wrongAnswers:[],
            timer:null,
            completed:false,
            failed:false,
            combos:[],
            winningCombo:[],
            stopGame:false,
        }
    },
    methods: {
        getQuestions(){
            const data = {
                kind_id: this.$route.params.kind_id,
                speed_id: this.$route.params.speed_id,
            }
            this.axios.post(`${this.$apiUrl}questions`, data).then(response => {
                this.questions = response.data.questions;
                this.answers = response.data.answers;
                this.sign = response.data.sign;
                this.questionTime = response.data.time;
                this.combos = response.data.combos;
                this.currentQuestion = this.questions[this.currentQuestionIndex];
                this.startTimer();
            }).catch(error => {
                console.log(error);
            });
        },
        markCorrect(answer, answerIndex){
            // this.playAudio('correct-answer.mp3');
            this.correctAnswers.push(answer);
            this.currentQuestion.is_done = true;
            this.correctIndexes.push(answerIndex);
            this.isCorrectorWrong(answer, answerIndex);
            this.isOver(answer, answerIndex);
        },
        markIncorrect(){
            let correctAnswer = this.answers.find(answer => answer.question_id == this.currentQuestion.question_id);
            let correctAnswerIndex = this.answers.findIndex(answer => answer.question_id == this.currentQuestion.question_id);
            this.wrongAnswers.push(correctAnswer);
            this.isCorrectorWrong(correctAnswer, correctAnswerIndex);
            // this.playAudio('wrong-answer.wav');
        },
        guessAnswer(answer, answerIndex){
            if(!this.correctAnswers.includes(answer) && !this.wrongAnswers.includes(answer)){
                if(answer.answer == this.currentQuestion.answer){
                    this.markCorrect(answer, answerIndex)
                }else{
                    this.markIncorrect();
                }
                this.currentQuestionIndex++;
                if(!this.isCompleted()){
                    this.currentQuestion = this.questions[this.currentQuestionIndex];
                    this.startTimer();
                }
            }
        },
        timeOut(){
            clearInterval(this.timer);
            this.markIncorrect();
            this.currentQuestionIndex++;
            if(!this.isCompleted()){
                this.currentQuestion = this.questions[this.currentQuestionIndex];
                this.startTimer();
            }
        },
        isCorrectorWrong(answer, answerIndex, isWon = false){
            if(isWon){
                document.getElementById(`answer-${answerIndex}`).classList.add('winning-answer')
            }else{
                if(this.correctAnswers.includes(answer)){
                    document.getElementById(`answer-${answerIndex}`).classList.add('correct-answer')
                }else if(this.wrongAnswers.includes(answer)){
                    document.getElementById(`answer-${answerIndex}`).classList.add('wrong-answer')
                }
            }
        },
        startTimer(){
            clearInterval(this.timer);
            this.remainingTime = this.questionTime;
            this.timer = setInterval(() => {
                this.remainingTime--;
                if(this.remainingTime == 0){
                    this.timeOut();
                }
                console.log("Something going on::", this.remainingTime);
            }, 1000);
        },
        isCompleted(){
            if(this.currentQuestionIndex == 25){
                this.isOver();
                return true;
            }else{
                return false;
            }
        },
        isOver(lastAnswer, lastAnswerIndex){
            let check = false;
            this.combos.forEach(combo => {
                let totalCorrect = 0;
                for(let i = 0; i < combo.length; i++){
                    if(this.correctIndexes.includes(combo[i])){
                        totalCorrect++;
                    }
                }
                if(totalCorrect == combo.length){
                    this.playAudio('win.mp3');
                    this.winningCombo = combo;
                    this.completed = true;
                    this.stopGame = true;
                    this.remainingTime = 0;
                    this.currentQuestion = [],
                    this.isCorrectorWrong(lastAnswer, lastAnswerIndex, true);
                    this.changeWinningColor();
                    clearInterval(this.timer);
                    check = true;
                }
            });
            if(!check && this.currentQuestionIndex == 25){
                this.playAudio('game-over.mp3');
                clearInterval(this.timer);
                this.failed = true;
                this.stopGame = true;
                
            }
        },
        changeWinningColor(){
            for(let i = 0; i < this.winningCombo.length; i++){
                document.getElementById(`answer-${this.winningCombo[i]}`).classList.remove('correct-answer');
                document.getElementById(`answer-${this.winningCombo[i]}`).classList.add('winning-answer');
            }
        }
    },
    created() {
        this.getQuestions();
    },
}
</script>