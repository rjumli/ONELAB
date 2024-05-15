<template>
    <Head title="Activation" />

    <div class="auth-page-wrapper pt-5 d-flex justify-content-center align-items-center min-vh-100">
       
        <div class="auth-page-content">
            <BContainer>
                

                <BRow class="justify-content-center">
                    <BCol md="8" lg="6" xl="5">
                        <BCard no-body class="mt-4">

                            <BCardBody class="p-4">
                                <!-- <div class="mb-4">
                                    <div class="avatar-lg mx-auto">
                                        <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                            <i class="ri-rotate-lock-line"></i>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                                        <img :src="$page.props.user.data.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="previewImage"/>
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <h5 class="fs-16 mb-1">{{ $page.props.user.data.name }}</h5>
                                    <p class="text-muted mb-0">{{ $page.props.user.data.assigned_laboratory }} | {{ $page.props.user.data.assigned_role }}</p>
                                </div>

                                <div class="p-2 mt-4">
                                    <div class="text-muted text-center mb-4 mx-lg-3">
                                        <!-- <h4 class="fs-15">Two Factor Authentication</h4> -->
                                        <div class="text-sm fs-12 text-muted">
                                            <template v-if="! recovery">
                                                Please confirm access to your account by entering the authentication code provided by your authenticator application.
                                            </template>
                                
                                            <template v-else>
                                                Please confirm access to your account by entering one of your emergency recovery codes.
                                            </template>
                                        </div>
                                    </div>

                                    <form class="customform" @submit.prevent="submit">
                                        
                                        <div class="mt-4">
                                            <BButton v-if="uploaded" variant="primary" class="w-100" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Activate</BButton>
                                            <BButton v-else variant="primary" class="w-100" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="true">Activate</BButton>
                                        </div>

                                         <div class="mt-4 text-center">
                                            <p class="mb-0"> Aren't ready yet?
                                                <a style="cursor: pointer;" @click.prevent="logout" class="fw-semibold text-danger">Logout</a>
                                            </p>
                                        </div>
                                        
                                    </form>
                                </div>
                            </BCardBody>
                        </BCard>
                    </BCol>
                </BRow>
            </BContainer>
        </div>
    </div>
</template>
<script>
import { useForm } from "@inertiajs/vue3"
export default {
    layout: null,
    data(){
        return {
            form: useForm({
                image: null,
            }),
            uploaded: false
        }
    },
    methods: {
        previewImage(e) {
            var fileInput = document.querySelector(".profile-img-file-input");
            var preview = document.querySelector(".user-profile-image");
            var file = fileInput.files[0];
            this.form.image = file;
            var reader = new FileReader();

            reader.addEventListener("load", () => { 
                preview.src = reader.result;
                this.form.post('/profile', {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.uploaded = true;
                    },
                });
            }, false);

            if (file) { 
                reader.readAsDataURL(file); 
            }
        },
    }
}
</script>
<script setup>
import { router } from '@inertiajs/vue3';
    const submit = () => {
        router.post('/activate');
    };
    const logout = () => {
        router.post('/logout');
    };
</script>
<style>
.auth-page-wrapper {
    background-color: hsl(201, 80%, 82%);
}
</style>
