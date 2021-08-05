<template>
    <v-container class="v-centered">
        <h3 class="text-center mt-20">Welcome to E-Product</h3>
        <v-row align="center" justify="center">
            <v-col cols="12" md="5" lg="5">
                <v-card elevation="5" class="login-card d-flex align-content-center flex-wrap ">
                    <v-card-title style="margin: 0 auto" class="mb-2"> Login</v-card-title>
                    <v-card-text>
                        <v-form ref="loginForm" method="post" v-model="valid" lazy-validation v-on:submit="validate">
                            <v-row no-gutters>
                                <v-col cols="12">
                                    <v-text-field v-model="form.email" :rules="loginEmailRules" label="E-mail" required outlined></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field outlined v-model="form.password" :append-icon="show1 ? 'eye' : 'eye-off'" :rules="[rules.required, rules.min]" :type="show1 ? 'text' : 'password'" name="input-10-1" label="Password" hint="At least 4 characters" autocomplete="off">
                                        @click:append="show1 = !show1" ></v-text-field>
                                </v-col>
                                <v-col class="d-flex" cols="12" sm="6" xsm="12" v-if="error">
                                    <p class="danger">{{ error }}</p>
                                </v-col>
                                <v-col cols="12">
                                    <v-btn block large type="submit" :disabled="!valid" color="primary" elevation="7" :loading="isLoading">
                                        Login
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
export default {
    computed: {},
    data() {
        return {
            isLoading: false,
            valid: true,
            error: null,
            form: {
                email: "",
                password: ""
            },
            loginEmailRules: [
                v => !!v || "Required",
                v => /.+@.+\..+/.test(v) || "E-mail must be valid"
            ],
            show1: false,
            rules: {
                required: value => !!value || "Required.",
                min: v => (v && v.length >= 4) || "Min 4 characters"
            }
        }
    },
    methods: {
        async validate(e) {
            e.preventDefault();
            this.error = null;
            if (this.$refs.loginForm.validate()) {
                this.isLoading = true;
                axios.post('api/auth/login', this.form)
                    .then(res => {
                        this.$store.dispatch('saveToken', {
                            token: res.data.access_token,
                        }, )
                         this.isLoading = false;
                        this.$router.push('/dashboard')
                    })
                    .catch(error => {
                        this.isLoading = false;
                        if (error.response.status == 422) {
                            this.error.push("Please enter valid Email or Password")
                        } else {
                            this.error.push("error,try again")
                        }
                    })

            }
        },

        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        }
    },
};

</script>
<style scoped>
.mt-20 {
    margin-top: 20px;
}

.login-card {
    margin-top: 50px;
}

.v-application .pa-7 {
    padding: 15px !important;
}

.danger {
    color: red;
}

</style>
