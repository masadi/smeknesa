<template>
  <b-row>
    <b-col cols="8">
      <b-card :title="title">
        <b-form-group label="Judul Artikel" label-for="post_title" :invalid-feedback="feedback.post_title"
          :state="state.post_title">
          <b-form-input v-model="form.post_title" placeholder="Judul Artikel"></b-form-input>
        </b-form-group>
        <b-form-group label="Konten Artikel" label-for="post_content" :invalid-feedback="feedback.post_content"
          :state="state.post_content">
          <quill-editor v-model="form.post_content" :options="snowOption" />
        </b-form-group>
        <b-button variant="outline-primary" @click="savePost">Publish</b-button>
      </b-card>
    </b-col>
    <b-col cols="4">
      <b-card>
        <b-form-group label="Status" label-for="post_status" :invalid-feedback="feedback.post_status"
          :state="state.post_status">
          <v-select id="post_status" v-model="form.post_status" :options="['publish', 'draft']"
            placeholder="== Pilih Status ==" :searchable="false" :state="state.tingkat">
            <template #no-options="{ search, searching, loading }">
              Tidak ada data untuk ditampilkan
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label="Gambar/Foto" label-for="post_image" :invalid-feedback="feedback.post_image"
          :state="state.post_image">
          <b-form-file id="wildcard" accept="image/*" :state="state.post_image" v-model="form.post_image" />
        </b-form-group>
        <app-collapse>
          <app-collapse-item title="Kategori">
            <b-form-group label="Kategori">
              <b-form-checkbox v-for="option in categories" :key="option.id" v-model="form.category_id"
                :value="option.id" name="category">
                {{ option.name }}
              </b-form-checkbox>
            </b-form-group>
            <a href="javascript:void(0)" @click="addCategory">Tambah Kategori</a>
            <template v-if="showFormCategory">
              <b-form-group label="Nama Kategori" label-for="name" :invalid-feedback="feedback.name"
                :state="state.name">
                <b-form-input v-model="cat.name" placeholder="Nama Kategori"></b-form-input>
              </b-form-group>
              <b-form-group label="Induk Kategori" label-for="category_id" :invalid-feedback="feedback.category_id"
                :state="state.category_id">
                <v-select id="category_id" v-model="cat.category_id" :reduce="name => name.id" label="name"
                  :options="categories" placeholder="== Pilih Induk Kategori ==" :searchable="false"
                  :state="state.category_id">
                  <template #no-options="{ search, searching, loading }">
                    Tidak ada data untuk ditampilkan
                  </template>
                </v-select>
              </b-form-group>
              <b-button variant="outline-primary" @click="saveCategory">Simpan Kategori</b-button>
            </template>
          </app-collapse-item>
          <app-collapse-item title="Tag">
            <b-form-group label="Tambah Tag" label-for="tags" description="Pisahkan dengan koma atau tombol Enter.">
              <b-form-tags id="tags" placeholder="Edukasi, Informasi, Dll" v-model="form.tags" input-id="tags-pills" tag-variant="primary" tag-pills separator="," />
            </b-form-group>
          </app-collapse-item>
        </app-collapse>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { BRow, BCol, BCard, BFormGroup, BFormInput, BButton, BFormCheckbox, BFormTags, BFormFile } from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import vSelect from 'vue-select'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
export default {
  components: {
    quillEditor,
    vSelect,
    AppCollapse,
    AppCollapseItem,
    BRow,
    BCol,
    BCard,
    BFormGroup,
    BFormInput,
    BButton,
    BFormCheckbox,
    BFormTags,
    BFormFile,
  },
  data() {
    return {
      title: null,
      showFormCategory: false,
      snowOption: {
        theme: 'snow',
      },
      form: {
        id: '',
        post_title: null,
        post_content: null,
        post_status: 'publish',
        post_image: null,
        category_id: [],
        tags: [],
      },
      feedback: {
        post_title: null,
        post_content: null,
        post_status: null,
        post_image: null,
        name: null,
        category_id: null,
      },
      state: {
        post_title: null,
        post_content: null,
        post_status: null,
        post_image: null,
        name: null,
        category_id: null,
      },
      cat: {
        name: null,
        category_id: null,
      },
      categories: [],
      loading_cat: false,
      loading_post: false,
    }
  },
  created() {
    this.getCategory()
    console.log(this.$route.params.id);

    if (this.$route.params.id) {
      this.title = 'Edit Artikel'
      this.getPost(this.$route.params.id)
    } else {
      this.title = 'Tambah Artikel Baru'
    }
  },
  methods: {
    getPost(id) {
      this.$http.post('/post/show', {
        id: id,
      }).then(res => {
        let getData = res.data
        console.log(getData);
        this.form.id = getData.id
        this.form.post_title = getData.post_title
        this.form.post_content = getData.post_content
        this.form.post_status = getData.post_status
        let category_id = [];
        getData.categories.forEach(cat => {
          category_id.push(cat.id)
        });
        this.form.category_id = category_id
        let tags = [];
        getData.tags.forEach(tag => {
          tags.push(tag.name)
        });
        this.form.tags = tags
      });
    },
    getCategory() {
      this.$http.get('/post/category').then(res => {
        let getData = res.data
        this.categories = getData.data
      })
    },
    addCategory() {
      this.showFormCategory = !this.showFormCategory
    },
    saveCategory() {
      this.loading_cat = true
      this.$http.post('/post/save-category', this.cat).then(res => {
        this.loading_cat = false;
        let getData = res.data
        if (getData.errors) {
          this.feedback.name = (getData.errors.name) ? getData.errors.name.join(', ') : ''
          this.state.category_id = (getData.errors.category_id) ? false : null
        } else {
          this.categories = getData.data
          this.cat.name = null
          this.cat.category_id = null
          this.showFormCategory = !this.showFormCategory
        }
      });
    },
    savePost() {
      this.loading_post = true
      let data = new FormData();
      data.append('id', this.form.id);
      data.append('post_title', this.form.post_title);
      data.append('post_content', this.form.post_content);
      data.append('post_status', this.form.post_status);
      data.append('post_image', this.form.post_image);
      data.append('category_id', JSON.stringify(this.form.category_id));
      data.append('tags', JSON.stringify(this.form.tags));
      this.$http.post('/post/save', data).then(res => {
        this.loading_post = false;
        let getData = res.data
        if (getData.errors) {
          this.feedback.post_title = (getData.errors.post_title) ? getData.errors.post_title.join(', ') : ''
          this.feedback.post_content = (getData.errors.post_content) ? getData.errors.post_content.join(', ') : ''
          this.feedback.post_status = (getData.errors.post_status) ? getData.errors.post_status.join(', ') : ''
          this.feedback.post_image = (getData.errors.post_image) ? getData.errors.post_image.join(', ') : ''
          this.state.post_title = (getData.errors.post_title) ? false : null
          this.state.post_content = (getData.errors.post_content) ? false : null
          this.state.post_status = (getData.errors.post_status) ? false : null
          this.state.post_image = (getData.errors.post_image) ? false : null
        } else {
          this.$router.push({ name: 'post-artikel' })
        }
      });
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/quill.scss';
</style>