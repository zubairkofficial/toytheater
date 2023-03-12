<template lang="">
    <div class="container">
        <div class="text-center mt-10">
            <img src="/assets/images/tutor-teaching-student.svg" class="home-tutor-img">
        </div>
        <div class="text-center mt-10">
            <h1>What Kind?</h1>
        </div>
        <div class="row">
            <Option v-for="kind in kinds" :key="kind.id" :text="kind.text" v-on:click="selectKind(kind.id)" :isSelected="selectedKind.id == kind.id" />
        </div>
        <div class="text-center mt-10">
            <h1>How Fast?</h1>
        </div>
        <div class="row">
            <Option v-for="speed in speeds" :key="speed.id" :text="`${speed.text} - ${speed.time}s`" v-on:click="selectSpeed(speed.id)" :isSelected="selectedSpeed.id == speed.id" />
        </div>
        <div class="text-center mt-50">
            <button class="btn-start" v-on:click="goToCalculator">Start Now</button>
        </div>
    </div>
</template>
<script>
import Option from '../components/Option.vue';
export default {
    components:{
        Option,
    },
    data() {
        return {
            kinds:[],
            speeds:[],
            selectedKind:{},
            selectedSpeed:{},
        }
    },
    methods: {
        getKinds(){
            this.axios.get(`${this.$apiUrl}kinds`).then(response => {
                this.kinds = response.data;
                this.selectedKind = this.kinds[0];
            });
        },
        getSpeeds(){
            this.axios.get(`${this.$apiUrl}speeds`).then(response => {
                this.speeds = response.data;
                this.selectedSpeed = this.speeds[0];
            });
        },
        selectKind(kind_id){
            this.selectedKind = this.kinds.find(kind => kind.id == kind_id);
        },
        selectSpeed(speed_id){
            this.selectedSpeed = this.speeds.find(speed => speed.id == speed_id);
        },
        goToCalculator(){
            if(this.selectedSpeed.id && this.selectedKind.id){
                this.playAudio('start-game.mp3');
                this.$router.push(`/calculator/${this.selectedKind.id}/${this.selectedSpeed.id}`);
            }
        }
    },
    created() {
        this.getKinds();
        this.getSpeeds();
    },
}
</script>