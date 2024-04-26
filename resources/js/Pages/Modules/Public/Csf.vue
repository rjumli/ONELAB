<template>
    <Head title="Customer Satisfaction Feedback"/>
    <div class="auth-page-wrapper pt-4 d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-page-content">
            <BContainer style="max-width: 1000px;">

                <BRow class="justify-content-center">
                    <BCol md="12">
                        <BCard no-body>
                            <BCardBody>
                                <div class="row mb-n3 mt-n1">
                                    <div class="col-1">
                                        <img src="@assets/images/logo-sm.png" alt="" class="avatar-sm">
                                    </div>
                                    <div class="col-11 ms-n4">
                                        <div class="text-primary mt-1">
                                            <p class="mb-0">DEPARTMENT OF SCIENCE AND TECHNOLOGY - Regional Standards and Testing Laboratory</p>
                                            <p class="fw-semibold fs-16 mt-n1">Customer Satisfaction Feedback Survey</p>
                                        </div>
                                    </div>
                                </div>
                            </BCardBody>
                        </BCard>
                        <BCard no-body class="mt-n2">
                            <BCardBody class="p-4">
                                <!-- <div class="alert alert-warning mb-xl-0 fs-10 fw-semibold" role="alert">RSTL-IX is committed to uphold the rights of individuals to data privacy and shall adhere to the provisions of Republic Act No. 10173 or also known as the Data Privacy Act of 2012. All information collected will be treated with utmost confidentiality.</div> -->
                                <div class="row customform">
                                    <div class="col-md-12">
                                        <!-- <InputLabel for="region" value="TSR Code"/> -->
                                        <Multiselect label="name"
                                        :options="tsrs" 
                                        :searchable="true" 
                                        placeholder="Select TSR Code"/>
                                    </div>
                                </div>
                            </BCardBody>
                        </BCard>
                        <BCard no-body class="mt-n2">
                            <BCardBody class="ps-4 pe-4 mb-n3">
                                   <b-progress class="progress-lg rounded-pill" style="height: 6px; margin-top: 20px; margin-bottom: -19px;">
                                    <b-progress-bar :value="progressbarvalue" variant="primary" aria-valuemax="100" class="rounded-0" />
                                </b-progress>
                                <div id="custom-progress-bar" class="progress-nav mb-4">
                                    <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist" style="z-index: 2;">
                                        <li class="nav-item" role="presentation" v-for="(tab, index) in questions" :key="index">
                                            <button class="nav-link rounded-pill" id="pills-gen-info-tab" type="button"
                                                role="tab" :class="{ active: activeTab == index+1, done: activeTab > index+1 }"
                                                @click="toggleTab(index+1);" :disabled="tab.is_disabled">{{index+1}}</button>
                                        </li>
                                    </ul>
                                </div>
                            </BCardBody>
                        </BCard>
                        <BCard no-body class="mt-n2">
                            <div class="card-header">
                                <h4 class="card-title text-center fs-16 mb-0">How would you rate our RSTL services?</h4>
                            </div>
                            <BCardBody class="ps-4 pe-4 pt-2 pb-4" style="height: 300px; overflow: hidden;">
                                <form action="#">
                                    <div class="tab-content">
                                        <div v-for="(tab, index) in questions" :key="index">
                                            <transition name="fade" @before-enter="beforeEnter" @after-enter="afterEnter">
                                                <div class="tab-pane fade" v-if="activeTab === index+1" :class="{'fade-transition show active': activeTab === index+1}" id="pills-gen-info"
                                                    role="tabpanel" aria-labelledby="pills-gen-info-tab">
                                                    <div v-if="tab.is_rating && !tab.is_overall">
                                                        <h6 class="mt-2 mb-3 fs-18 text-center text-primary">{{tab.name}}</h6>
                                                        <div class="text-center mb-4" style="cursor: pointer;">
                                                            <div v-for="(smiley, index1) in smileys" :key="index1" class="smiley-container">
                                                                <i :class="[smiley.icon, { 'active bx-tada': (tab.color === smiley.color) ? true : false}]" 
                                                                @click="toggleActive(index,smiley.score,smiley.color,tab.is_rating)"
                                                                :style="(tab.color === smiley.color) ? 'color: '+ smiley.color : 'color: #777777'"
                                                                style="font-size: 55px;"></i>
                                                                <span class="smiley-text text-muted mt-0">{{ smiley.text }}</span>
                                                            </div>
                                                        </div>

                                                        <h6 class="mt-4 mb-3 fs-16 text-primary text-center">How important is this attribute?</h6>
                                                        <div class="text-center mb-1" style="cursor: pointer;">
                                                            <div v-for="(num, index2) in numbers" :key="index2" class="smiley-container">
                                                                <center>
                                                                    <div class="avatar-sm" @click="toggleActive2(index,index2,num.score,tab.is_rating)">
                                                                        <span 
                                                                        :style="(tab.importance === num.score) ? 'background-color: '+ num.color : 'background-color: #777777'"
                                                                        class="avatar-title rounded-circle fs-17">{{ num.score }}</span>
                                                                    </div>
                                                                </center>
                                                                <span class="smiley-text text-muted mt-1">{{ num.text }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else-if="tab.is_rating && tab.is_overall">
                                                        <h6 class="mt-5 mb-3 fs-14 text-center text-primary">{{tab.name}}</h6>
                                                        <div class="text-center mt-4 mb-1" style="cursor: pointer;">
                                                            <div v-for="i in 10" :key="i" class="number-container">
                                                                <center>
                                                                    <div class="avatar-sm" @click="toggleActive4(index,i,tab.is_rating)">
                                                                        <span 
                                                                        :style="(tab.rating === i) ? 'background-color: #6cc5cd' : 'background-color: #777777'"
                                                                        class="avatar-title rounded-circle fs-17">{{ i }}</span>
                                                                    </div>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-else-if="tab.is_comment">
                                                        
                                                    <BRow class="g-3 customform">
                                                        <BCol lg="12">
                                                            <InputLabel for="description" value="Please write your comment/suggestions below. (Optional)" />
                                                            <Textarea id="description" maxlength="250" rows="3" type="text" class="form-control" autofocus placeholder="Please enter description" style="background-color: #f5f6f7;"/>
                                                        </BCol>
                                                        <BCol lg="12">
                                                            <InputLabel for="description" value="Please indicate other attribute's which you think is important to your needs.  (Optional)" />
                                                            <Textarea id="description" maxlength="250" rows="3" type="text" class="form-control" autofocus placeholder="Please enter description" style="background-color: #f5f6f7;"/>
                                                        </BCol>
                                                    </BRow>
                                                    </div>
                                                    <div v-else>
                                                        <h6 class="mt-4 mb-3 fs-16 text-primary">{{tab.name}}</h6>

                                                        <div class="form-check mb-2" v-for="(answer,i) in answers[index]" :key="i">
                                                            <input type="radio" @click="toggleActive3(index,i,tab.is_rating)" class="form-check-input" v-model="tab.answer" :value="i" :id="'checkbox-' +i">
                                                            <label class="form-check-label" :for="'checkbox-' +i">{{answer}}</label>
                                                        </div>
                                                    </div>
                                                     <div class="card-footer text-center">
                                <div class="mb-0 mt-n1 d-flex align-items-start" >
                                    <button type="button" class="btn btn-light btn-label previestab" @click="toggleTab(prev);"><i class="ri-arrow-left-line label-icon align-middle fs-lg me-2"></i> Back</button>
                                    <button type="button" class="btn btn-primary btn-label right ms-auto nexttab nexttab" @click="toggleTab(next);" :disabled="next_status"><i class="ri-arrow-right-line label-icon align-middle fs-lg ms-2"></i>Next</button>
                                </div>
                            </div>
                                                </div>
                                                
                                            </transition>
                                        </div>
                                    </div>
                                </form>
                            </BCardBody>
                           
                        </BCard>

                    </BCol>
                </BRow>
            </BContainer>
        </div>

    </div>
</template>
<script setup>
import Multiselect from "@vueform/multiselect";
import { useForm } from '@inertiajs/vue3';
import Checkbox from '@/Shared/Components/Forms/Checkbox.vue';
import InputError from '@/Shared/Components/Forms/InputError.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextArea from '@/Shared/Components/Forms/Textarea.vue';
</script>
<script>
export default {
    props: ['tsrs','questions'],
    layout: null,
    data() {
        return {
            progressbarvalue: 0,
            activeTab: 1,
            prev: 1,
            next: 2,
            next_status: true,
            smileys: [
                { icon: 'bx bxs-happy-beaming', active: false, score: 5, color: '#ee9f03', text: 'Very Satisfied'},
                { icon: 'bx bxs-smile', active: false, score: 4, color: '#feea1a', text: 'Satisfied' },
                { icon: 'bx bxs-meh', active: false, score: 3, color: '#5cfd9b', text: 'Neither' },
                { icon: 'bx bxs-sad', active: false, score: 2, color: '#ff8f01', text: 'Dissatisfied' },
                { icon: 'bx bxs-angry', active: false, score: 1, color: '#dd0000', text: 'Very Dissatisfied' }
            ],
            numbers: [
                { score: 5, color: '#6cc5cd', text: 'Very Important'},
                { score: 4, color: '#6cc5cd', text: 'Important' },
                { score: 3, color: '#6cc5cd', text: 'Moderately' },
                { score: 2, color: '#6cc5cd', text: 'Slightly' },
                { score: 1, color: '#6cc5cd', text: 'Not at all' }
            ],
            answers:[
                [
                    'I know what a CC is and I saw this office\'s CC.',
                    'I know what a CC is but I did NOT see this office\'s CC.',
                    'I learned the CC when I saw this office\'s CC.',
                    'I do not know what a CC is  and I did not see one in this office.(Answer \'N/A\' on CC2 and CC3)'
                ],
                [
                   'Easy to use',
                    'Somewhat easy to see',
                    'Difficult to see',
                    'Not visible at all',
                    'n/a' 
                ],
                [
                    'Helped very much',
                    'Somewhat helped',
                    'Did not help',
                    'n/a'
                ]
            ],
        }
    },
    methods: {
        toggleActive(index,rate,color,is_rating) {
            this.questions[index].rating = rate;
            this.questions[index].color = color;
            this.isTabDisabled(index,is_rating);
        },
        toggleActive2(index,index2,rate,is_rating) {
            this.questions[index].importance = rate;
            this.isTabDisabled(index,is_rating);
        },
        toggleActive3(index,i,is_rating) {
            this.questions[index].answer = i;
            this.isTabDisabled(index,is_rating);
        },
        toggleActive4(index,i,is_rating) {
            this.questions[index].rating = i;
            this.isTabDisabled(index,3);
        },
        toggleTab(tab) {
            this.activeTab = tab;
            this.calculateProgress(tab);
            this.next_status = true;
        },
        calculateProgress(tab) {
            const progress = ((tab - 1) / (this.questions.length - 1)) * 100; 
            this.progressbarvalue = progress;
        },
        beforeEnter(el) {
            el.style.opacity = 0;
        },
        afterEnter(el) {
            setTimeout(() => { el.style.opacity = 1; }, 100); 
        },
        isTabDisabled(index,is_rating) {
            
            if(is_rating === 1){
                if(this.questions[index]){
                    if(!this.questions[index].rating || !this.questions[index].importance){
                        return true;
                    }else{
                        this.questions[index+1].is_disabled = false;
                        this.next_status = false;
                        this.next = wew+1;
                    }
                }
            }else if(is_rating === 3){
                if(this.questions[index]){
                    if(!this.questions[index].rating){
                        return true;
                    }else{
                        this.questions[index+1].is_disabled = false;
                        this.next_status = false;
                        this.next = wew+1;
                    }
                }
            }else{
                if(this.questions[index]){
                    if(this.questions[index].answer === null){
                        return true;
                    }else{
                        var wew = index+1;
                        this.questions[index+1].is_disabled = false;
                        this.next_status = false;
                        this.next = wew+1;
                    }
                }
            }
        },
    }
}
</script>
<style>
.auth-page-wrapper {
    background-color: hsl(201, 80%, 82%);
}
.smiley-container {
  display: inline-block;
  text-align: center;
  width: 150px;
}
.number-container {
  display: inline-block;
  text-align: center;
  width: 65px;
}
.circle-icon {
  font-size: 60px;
  display: inline-block;
  position: relative;
}

.smiley-text {
  display: block;
  margin-top: 5px;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

</style>
