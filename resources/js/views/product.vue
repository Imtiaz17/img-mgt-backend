<template>
    <v-container grid-list-sm>
        <v-row justify="center">
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        ADD Product
                        <v-spacer />
                        <v-icon aria-label="Close" @click="closedialog"> mdi-close </v-icon>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-form ref="form" v-model="valid" lazy-validation>
                                <v-row no-gutters>
                                    <v-col cols="12" sm="6" md="12">
                                        <v-select v-model="form.category_id" :items="categories" label="Category" item-text="name" item-value="id" dense outlined></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="12">
                                        <v-select v-model="form.subcategory_id" :items="subcats" label="Sub Category" item-text="name" item-value="id" dense outlined></v-select>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="12">
                                        <v-text-field outlined dense label="Name" required :rules="nameRules" v-model="form.name"></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6" md="12">
                                        <div id="my-strictly-unique-vue-upload-multiple-image" style="display: flex; justify-content: center;">
                                            <vue-upload-multiple-image @upload-success="uploadImageSuccess" @before-remove="beforeRemove" @edit-image="editImage" :data-images="form.images" idUpload="myIdUpload" editUpload="myIdEdit" dragText="Drag or select picture" browseText="Browse" markIsPrimaryText="" primaryText=""></vue-upload-multiple-image>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closedialog">
                            Close
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="submitForm">
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="confirmation" max-width="300">
                <v-card>
                    <v-card-title>
                        Are you sure?
                        <v-spacer />
                        <v-icon aria-label="Close" @click="confirmation = false">
                            mdi-close
                        </v-icon>
                    </v-card-title>
                    <v-card-text class="pb-6 pt-12 text-center">
                        <v-btn class="mr-3" text @click="confirmation = false"> No </v-btn>
                        <v-btn color="success" text @click="confirmDelete()"> Yes </v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-row>
        <v-row>
            <v-col>
                <v-btn tile dark color="indigo" class="float-right" @click="opendialog">
                    <v-icon left> mdi-plus </v-icon>
                    ADD
                </v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" md="12">
                <v-card v-if="isLoading">
                    <v-skeleton-loader class="mx-auto" type="table"></v-skeleton-loader>
                </v-card>
                <v-card v-else>
                    <v-card-title>
                        LIST
                        <v-spacer></v-spacer>
                        <v-text-field solo dense v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details @click:append="searchItem"></v-text-field>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table :headers="headers" :items="items">
                            <template v-slot:item.actions="{ item }">
                                <v-menu open-on-hover top offset-y>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn color="primary" dark small v-bind="attrs" v-on="on">
                                            <v-icon dark> mdi-dots-horizontal </v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list small>
                                        <v-list-item link @click="editItem(item)">
                                            <v-list-item-title>
                                                <v-icon> mdi-pencil </v-icon>Edit
                                            </v-list-item-title>
                                        </v-list-item>
                                        <v-list-item link @click="deleteItem(item)">
                                            <v-list-item-title>
                                                <v-icon> mdi-delete </v-icon> Delete
                                            </v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </template>
                        </v-data-table>
                        <v-pagination v-model="pagination.current" :length="pagination.total" @input="onPageChange"></v-pagination>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import VueUploadMultipleImage from 'vue-upload-multiple-image'
export default {
    components: {
        VueUploadMultipleImage
    },
    name: "Products",
    data() {
        return {
            add: false,
            edit: false,
            search: "",
            isLoading: false,
            confirmation: false,
            update: false,
            pagination: {
                current: 1,
                total: 0
            },
            proid: "",
            categories: [],
            subcats: [],
            items: [],
            singleItem: {},
            dialog: false,
            valid: true,
            nameRules: [(v) => !!v || "Catergory is required"],
            form: {
                name: "",
                category_id: "",
                subcategory_id: "",
                images: []
            },
        };
    },
    computed: {
        headers() {
            return [{
                    sortable: false,
                    text: 'Product Name',
                    value: "name"
                },
                {
                    sortable: false,
                    text: 'Category',
                    value: "category"
                },
                {
                    sortable: false,
                    text: 'Sub-Category',
                    value: "subcategory"
                },
                {
                    sortable: false,
                    text: 'Actions',
                    value: "actions"
                }
            ];
        }
    },

    mounted() {
        this.getProducts()
        this.getCategories();
    },
    methods: {
        uploadImageSuccess(formData, index, fileList) {
            this.form.images = fileList
        },
        beforeRemove(index, done, fileList) {
            var r = confirm("remove image")
            if (r == true) {
                this.form.images.splice(this.form.images.indexOf(index), 1);
                done()
            } else {}
        },
        editImage(formData, index, fileList) {
            setTimeout(() => {
                this.form.images = fileList
            }, 2000);
        },
        opendialog() {
            this.dialog = true
        },
        closedialog() {
            this.dialog = !this.dialog
        },
        async getCategories() {
            this.isLoading = true;
            await axios.get("api/category").then(response => {
                this.categories = response.data.data;
                this.isLoading = false;
            });
        },
        async getProducts() {
            await axios.get("api/product?page="+this.pagination.current).then(response => {
                this.items = response.data.data;
                this.pagination.current = response.data.meta.current_page;
                this.pagination.total = response.data.meta.last_page;
                this.isLoading = false;
            });
        },
        onPageChange() {
            this.getProducts();
        },
        deleteItem(item) {
            this.confirmation = true;
            this.proid = item.id;
        },
        async confirmDelete() {
            await axios.delete(`api/product/${this.proid}`).then(res => {
                this.confirmation = false;
                this.getProducts();
            });
        },
        async searchItem() {
            await axios.get("api/search-product?keyword=" + this.search).then(response => {
                this.items = response.data.data;
                this.isLoading = false;
            });
        },
        async submitForm() {
            if (this.$refs.form.validate()) {
                if (this.edit == false) {
                    console.log('jdjd')
                    await axios.post("api/product", this.form).then((res) => {
                        this.$refs.form.reset();
                        this.dialog = false;
                        this.getProducts();
                    });

                } else {
                    await axios
                        .patch(`api/product/${this.proid}`, this.form)
                        .then((res) => {
                            this.$refs.form.reset();
                            this.dialog = false;
                            this.getProducts();
                        });
                }

            }
        },
        editItem(item) {
            this.dialog = true
            this.proid = item.id
            this.form.name = item.name
            this.form.category_id = item.category_id
            this.form.subcategory_id = item.subcategory_id
            this.edit = true;
        }
    },
    watch: {
        'form.category_id': function(val) {
            axios.get("api/getsubcategory?cat_id=" + val).then((res) => {
                this.subcats = res.data.data
            });

        },
        'form.images': function(val) {
            this.form.images = val
            console.log(val)
        }
    }
};

</script>
<style>
</style>
