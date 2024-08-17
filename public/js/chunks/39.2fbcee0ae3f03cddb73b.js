(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[39],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.array.join.js */ "./node_modules/core-js/modules/es.array.join.js");
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @core/utils/eventBus */ "./resources/js/src/@core/utils/eventBus.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_6__);




//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BOverlay: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BOverlay"],
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BForm"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BFormGroup"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BFormInput"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BCol"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BButton"],
    BFormTextarea: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BFormTextarea"],
    BTableSimple: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BTableSimple"],
    BThead: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BThead"],
    BTbody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BTbody"],
    BTr: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BTr"],
    BTh: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BTh"],
    BTd: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BTd"],
    BFormCheckbox: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BFormCheckbox"],
    BLink: bootstrap_vue__WEBPACK_IMPORTED_MODULE_4__["BLink"],
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_6___default.a
  },
  data: function data() {
    return {
      addModalShow: false,
      loading_form: false,
      loading_table: false,
      data_pkl: [],
      data_siswa: [],
      form: {
        data: 'pembimbing',
        pkl_id: '',
        peserta_didik_id: {},
        deskripsi: {},
        catatan: {}
      },
      feedback: {
        pkl_id: ''
      },
      state: {
        pkl_id: null
      }
    };
  },
  created: function created() {
    this.form.guru_id = this.user.guru_id;
    this.form.semester_id = this.user.semester.semester_id;
    _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_5__["default"].$on('open-modal-add-nilai-pkl', this.handleEvent);
  },
  methods: {
    handleEvent: function handleEvent() {
      _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_5__["default"].$emit('loading', true);
      this.getPkl();
    },
    getPkl: function getPkl() {
      var _this2 = this;
      this.$http.post('/magang/get-pkl', this.form).then(function (response) {
        var getData = response.data;
        _this2.addModalShow = true;
        _this2.data_pkl = getData;
        _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_5__["default"].$emit('loading', false);
      });
    },
    changePkl: function changePkl(val) {
      var _this3 = this;
      this.data_tp = [];
      this.data_siswa = [];
      if (val) {
        this.loading_table = true;
        this.$http.post('/magang/get-pd-pkl', this.form).then(function (response) {
          var getData = response.data;
          _this3.data_tp = getData.data_tp;
          _this3.data_siswa = getData.data_siswa;
          var _this = _this3;
          _this3.data_siswa.forEach(function (siswa) {
            siswa.nilai_pkl.forEach(function (nilai) {
              _this.form.peserta_didik_id["".concat(nilai.tp_id, "#").concat(siswa.peserta_didik_id)] = nilai.nilai;
              _this.form.deskripsi["".concat(nilai.tp_id, "#").concat(siswa.peserta_didik_id)] = nilai.deskripsi;
            });
            _this.form.catatan[siswa.peserta_didik_id] = siswa.pd_pkl.catatan;
          });
          console.log(_this3.form.catatan);
          _this3.loading_table = false;
        });
      }
    },
    hideModal: function hideModal() {
      this.addModalShow = false;
      this.resetForm();
    },
    resetForm: function resetForm() {
      this.data_tp = [];
      this.data_siswa = [];
      this.form.pkl_id = '';
      this.form.peserta_didik_id = {};
      this.form.deskripsi = {};
      this.form.catatan = {};
    },
    handleOk: function handleOk(bvModalEvent) {
      bvModalEvent.preventDefault();
      this.handleSubmit();
    },
    handleSubmit: function handleSubmit() {
      var _this4 = this;
      this.loading_form = true;
      this.$http.post('/magang/simpan-nilai', this.form).then(function (response) {
        _this4.loading_form = false;
        var getData = response.data;
        if (getData.errors) {
          _this4.state.pkl_id = getData.errors.pkl_id ? false : null;
          _this4.feedback.pkl_id = getData.errors.pkl_id ? getData.errors.pkl_id.join(', ') : '';
        } else {
          _this4.$swal({
            icon: getData.icon,
            title: getData.title,
            html: getData.text,
            allowOutsideClick: false,
            customClass: {
              confirmButton: 'btn btn-success'
            }
          }).then(function (result) {
            if (getData.success) {
              _this4.hideModal();
              _this4.$emit('reload');
            }
          });
        }
      })["catch"](function (error) {
        console.log(error);
      });
    },
    onContext: function onContext(ctx) {
      this.formatted = ctx.selectedFormatted;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.filter.js */ "./node_modules/core-js/modules/es.array.filter.js");
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @core/utils/eventBus */ "./resources/js/src/@core/utils/eventBus.js");


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BTableSimple: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BTableSimple"],
    BThead: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BThead"],
    BTbody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BTbody"],
    BTr: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BTr"],
    BTh: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BTh"],
    BTd: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BTd"]
  },
  data: function data() {
    return {
      detilModalShow: false,
      detil_data: null,
      data_siswa: [],
      data_tp: []
    };
  },
  created: function created() {
    _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_3__["default"].$on('open-modal-detil-nilai-pkl', this.handleEvent);
  },
  methods: {
    handleEvent: function handleEvent(data) {
      var _this = this;
      this.detil_data = data;
      this.$http.post('/magang/detil-nilai', {
        pkl_id: data.pkl_id
      }).then(function (response) {
        var getData = response.data;
        _this.data_siswa = getData.data_siswa;
        _this.data_tp = getData.data_tp;
        _this.$emit('stop_loading');
        _this.detilModalShow = true;
      });
    },
    hideModal: function hideModal() {
      this.detilModalShow = false;
      this.resetForm();
    },
    resetForm: function resetForm() {
      console.log('resetForm');
    },
    getNilaiTp: function getNilaiTp(tp_id, nilai_pkl) {
      var arrByID = nilai_pkl.filter(function (el) {
        return el.tp_id == tp_id;
      });
      if (arrByID.length) {
        return arrByID[0].nilai;
      }
      return '-';
    },
    getDeskTp: function getDeskTp(tp_id, nilai_pkl) {
      var arrByID = nilai_pkl.filter(function (el) {
        return el.tp_id == tp_id;
      });
      if (arrByID.length) {
        return arrByID[0].deskripsi;
      }
      return '-';
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_2__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BCol"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BFormInput"],
    BTable: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BTable"],
    BSpinner: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BSpinner"],
    BPagination: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BPagination"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BButton"],
    BOverlay: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BOverlay"],
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_2___default.a
  },
  props: {
    items: {
      type: Array,
      required: true
    },
    fields: {
      type: Array,
      required: true
    },
    meta: {
      required: true
    },
    isBusy: {
      type: Boolean,
      "default": function _default() {
        return true;
      }
    },
    loading: {
      type: Boolean,
      "default": function _default() {
        return false;
      }
    }
  },
  data: function data() {
    return {
      sortBy: null,
      sortDesc: false
    };
  },
  watch: {
    sortBy: function sortBy(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      });
    },
    sortDesc: function sortDesc(val) {
      this.$emit('sort', {
        sortBy: this.sortBy,
        sortDesc: this.sortDesc
      });
    }
  },
  methods: {
    aksi: function aksi(item, _aksi) {
      this.$emit('aksi', {
        aksi: _aksi,
        item: item
      });
    },
    loadPerPage: function loadPerPage(val) {
      this.$emit('per_page', this.meta.per_page);
    },
    changePage: function changePage(val) {
      this.$emit('pagination', val);
    },
    search: lodash__WEBPACK_IMPORTED_MODULE_0___default.a.debounce(function (e) {
      this.$emit('search', e);
    }, 500)
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.search.js */ "./node_modules/core-js/modules/es.string.search.js");
/* harmony import */ var core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _Datatable_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Datatable.vue */ "./resources/js/src/views/magang/input-nilai/Datatable.vue");
/* harmony import */ var _components_modal_magang_input_nilai_AddModal_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./../../components/modal/magang/input-nilai/AddModal.vue */ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue");
/* harmony import */ var _components_modal_magang_input_nilai_DetilModal_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./../../components/modal/magang/input-nilai/DetilModal.vue */ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue");
/* harmony import */ var _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @core/utils/eventBus */ "./resources/js/src/@core/utils/eventBus.js");


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


 //IMPORT COMPONENT DATATABLENYA



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BCard"],
    BCardBody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BCardBody"],
    BSpinner: bootstrap_vue__WEBPACK_IMPORTED_MODULE_2__["BSpinner"],
    Datatable: _Datatable_vue__WEBPACK_IMPORTED_MODULE_3__["default"],
    AddModal: _components_modal_magang_input_nilai_AddModal_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    DetilModal: _components_modal_magang_input_nilai_DetilModal_vue__WEBPACK_IMPORTED_MODULE_5__["default"]
  },
  data: function data() {
    return {
      loading: false,
      isBusy: true,
      fields: [{
        key: 'nama',
        label: 'Judul Magang',
        sortable: true,
        thClass: 'text-center'
      }, {
        key: 'guru',
        label: 'Nama Pembimbing',
        sortable: true,
        thClass: 'text-center'
      }, {
        key: 'dudi',
        label: 'DUDI',
        sortable: false,
        thClass: 'text-center',
        tdClass: 'text-center'
      }, {
        key: 'rombongan_belajar',
        label: 'Kelas',
        sortable: false,
        thClass: 'text-center',
        tdClass: 'text-center'
      }, {
        key: 'pd_pkl_count',
        label: 'Jml Siswa',
        sortable: false,
        thClass: 'text-center',
        tdClass: 'text-center'
      }, {
        key: 'actions',
        label: 'Aksi',
        sortable: false,
        thClass: 'text-center',
        tdClass: 'text-center'
      }],
      items: [],
      meta: {},
      current_page: 1,
      //DEFAULT PAGE YANG AKTIF ADA PAGE 1
      per_page: 10,
      //DEFAULT LOAD PERPAGE ADALAH 10
      search: '',
      sortBy: 'nama',
      //DEFAULT SORTNYA ADALAH CREATED_AT
      sortByDesc: false //ASCEDING
    };
  },
  created: function created() {
    _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_6__["default"].$on('add-nilai-pkl', this.handleEvent);
    this.loadPostsData();
  },
  methods: {
    handleEvent: function handleEvent() {
      _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_6__["default"].$emit('open-modal-add-nilai-pkl');
    },
    handleReload: function handleReload() {
      this.loadPostsData();
    },
    loadPostsData: function loadPostsData() {
      var _this = this;
      this.loading = true;
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      var current_page = this.current_page; //this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/magang', {
        params: {
          user_id: this.user.user_id,
          guru_id: this.user.guru_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(function (response) {
        //this.items = response.data.all_pd
        var getData = response.data.data;
        _this.isBusy = false;
        _this.loading = false;
        _this.items = getData.data; //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        _this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to
        };
      });
    },
    //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
    handlePerPage: function handlePerPage(val) {
      this.per_page = val; //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
      this.loadPostsData(); //DAN REQUEST DATA BARU KE SERVER
    },
    //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
    handlePagination: function handlePagination(val) {
      this.current_page = val; //SET CURRENT PAGE YANG AKTIF
      this.loadPostsData();
    },
    //JIKA ADA DATA PENCARIAN
    handleSearch: function handleSearch(val) {
      this.search = val; //SET VALUE PENCARIAN KE VARIABLE SEARCH
      this.loadPostsData(); //REQUEST DATA BARU
    },
    //JIKA ADA EMIT SORT
    handleSort: function handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy;
        this.sortByDesc = val.sortDesc;
        this.loadPostsData(); //DAN LOAD DATA BARU BERDASARKAN SORT
      }
    },
    handleAksi: function handleAksi(val) {
      this.loading = true;
      _core_utils_eventBus__WEBPACK_IMPORTED_MODULE_6__["default"].$emit("open-modal-".concat(val.aksi, "-nilai-pkl"), val.item);
    },
    handleLoading: function handleLoading() {
      this.loading = false;
    }
  }
});

/***/ }),

/***/ "./node_modules/core-js/internals/same-value.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/internals/same-value.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// `SameValue` abstract operation
// https://tc39.es/ecma262/#sec-samevalue
// eslint-disable-next-line es/no-object-is -- safe
module.exports = Object.is || function is(x, y) {
  // eslint-disable-next-line no-self-compare -- NaN check
  return x === y ? x !== 0 || 1 / x === 1 / y : x != x && y != y;
};


/***/ }),

/***/ "./node_modules/core-js/modules/es.array.filter.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/es.array.filter.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var $filter = __webpack_require__(/*! ../internals/array-iteration */ "./node_modules/core-js/internals/array-iteration.js").filter;
var arrayMethodHasSpeciesSupport = __webpack_require__(/*! ../internals/array-method-has-species-support */ "./node_modules/core-js/internals/array-method-has-species-support.js");

var HAS_SPECIES_SUPPORT = arrayMethodHasSpeciesSupport('filter');

// `Array.prototype.filter` method
// https://tc39.es/ecma262/#sec-array.prototype.filter
// with adding support of @@species
$({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT }, {
  filter: function filter(callbackfn /* , thisArg */) {
    return $filter(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});


/***/ }),

/***/ "./node_modules/core-js/modules/es.array.join.js":
/*!*******************************************************!*\
  !*** ./node_modules/core-js/modules/es.array.join.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var uncurryThis = __webpack_require__(/*! ../internals/function-uncurry-this */ "./node_modules/core-js/internals/function-uncurry-this.js");
var IndexedObject = __webpack_require__(/*! ../internals/indexed-object */ "./node_modules/core-js/internals/indexed-object.js");
var toIndexedObject = __webpack_require__(/*! ../internals/to-indexed-object */ "./node_modules/core-js/internals/to-indexed-object.js");
var arrayMethodIsStrict = __webpack_require__(/*! ../internals/array-method-is-strict */ "./node_modules/core-js/internals/array-method-is-strict.js");

var nativeJoin = uncurryThis([].join);

var ES3_STRINGS = IndexedObject != Object;
var FORCED = ES3_STRINGS || !arrayMethodIsStrict('join', ',');

// `Array.prototype.join` method
// https://tc39.es/ecma262/#sec-array.prototype.join
$({ target: 'Array', proto: true, forced: FORCED }, {
  join: function join(separator) {
    return nativeJoin(toIndexedObject(this), separator === undefined ? ',' : separator);
  }
});


/***/ }),

/***/ "./node_modules/core-js/modules/es.string.search.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/modules/es.string.search.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var call = __webpack_require__(/*! ../internals/function-call */ "./node_modules/core-js/internals/function-call.js");
var fixRegExpWellKnownSymbolLogic = __webpack_require__(/*! ../internals/fix-regexp-well-known-symbol-logic */ "./node_modules/core-js/internals/fix-regexp-well-known-symbol-logic.js");
var anObject = __webpack_require__(/*! ../internals/an-object */ "./node_modules/core-js/internals/an-object.js");
var isNullOrUndefined = __webpack_require__(/*! ../internals/is-null-or-undefined */ "./node_modules/core-js/internals/is-null-or-undefined.js");
var requireObjectCoercible = __webpack_require__(/*! ../internals/require-object-coercible */ "./node_modules/core-js/internals/require-object-coercible.js");
var sameValue = __webpack_require__(/*! ../internals/same-value */ "./node_modules/core-js/internals/same-value.js");
var toString = __webpack_require__(/*! ../internals/to-string */ "./node_modules/core-js/internals/to-string.js");
var getMethod = __webpack_require__(/*! ../internals/get-method */ "./node_modules/core-js/internals/get-method.js");
var regExpExec = __webpack_require__(/*! ../internals/regexp-exec-abstract */ "./node_modules/core-js/internals/regexp-exec-abstract.js");

// @@search logic
fixRegExpWellKnownSymbolLogic('search', function (SEARCH, nativeSearch, maybeCallNative) {
  return [
    // `String.prototype.search` method
    // https://tc39.es/ecma262/#sec-string.prototype.search
    function search(regexp) {
      var O = requireObjectCoercible(this);
      var searcher = isNullOrUndefined(regexp) ? undefined : getMethod(regexp, SEARCH);
      return searcher ? call(searcher, regexp, O) : new RegExp(regexp)[SEARCH](toString(O));
    },
    // `RegExp.prototype[@@search]` method
    // https://tc39.es/ecma262/#sec-regexp.prototype-@@search
    function (string) {
      var rx = anObject(this);
      var S = toString(string);
      var res = maybeCallNative(nativeSearch, rx, S);

      if (res.done) return res.value;

      var previousLastIndex = rx.lastIndex;
      if (!sameValue(previousLastIndex, 0)) rx.lastIndex = 0;
      var result = regExpExec(rx, S);
      if (!sameValue(rx.lastIndex, previousLastIndex)) rx.lastIndex = previousLastIndex;
      return result === null ? -1 : result.index;
    }
  ];
});


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
var ___CSS_LOADER_AT_RULE_IMPORT_0___ = __webpack_require__(/*! -!../../../../../../../../node_modules/css-loader/dist/cjs.js!sweetalert2/dist/sweetalert2.min.css */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sweetalert2/dist/sweetalert2.min.css");
exports = ___CSS_LOADER_API_IMPORT___(false);
exports.i(___CSS_LOADER_AT_RULE_IMPORT_0___);
// Module
exports.push([module.i, "/*\n$font-family-sans-serif: Public Sans,sans-serif,-apple-system,blinkmacsystemfont,Segoe UI,roboto,Helvetica Neue,arial,sans-serif,\"Apple Color Emoji\",\"Segoe UI Emoji\",Segoe UI Symbol !default;\n$font-family-monospace: Public Sans,sans-serif,-apple-system,blinkmacsystemfont,Segoe UI,roboto,Helvetica Neue,arial,sans-serif,\"Apple Color Emoji\",\"Segoe UI Emoji\",Segoe UI Symbol !default;\n*/\n[dir] .swal2-container .swal2-popup {\n  border-radius: 0.428rem;\n  padding-bottom: 1.5rem;\n}\n.swal2-container .swal2-popup .swal2-close:focus {\n  outline: none;\n}\n[dir] .swal2-container .swal2-popup .swal2-close:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup .swal2-content {\n  color: #6e6b7b;\n}\n.swal2-container .swal2-popup .swal2-title {\n  font-size: 1.5rem;\n  font-weight: inherit;\n  color: #5e5873;\n}\n[dir] .swal2-container .swal2-popup button.swal2-confirm:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup input.swal2-input {\n  height: 2.714rem;\n  font-size: 1rem;\n  color: #6e6b7b;\n  line-height: 1.45;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input {\n  padding: 0.438rem 1rem;\n  border-color: #d8d6de;\n  border-radius: 0.357rem;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input:focus {\n  border-color: #7367f0 !important;\n  box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step {\n  background-color: #7367f0;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step-line, [dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step {\n  background-color: #b3adf7;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step-line {\n  background-color: #7367f0;\n}\n.swal2-container .swal2-popup pre {\n  min-height: 1.35rem;\n}\n[dir] .swal2-container .swal2-popup pre {\n  margin-top: 0.5rem;\n}\n.swal2-container .swal2-popup .swal2-footer {\n  color: #6e6b7b;\n}\n[dir] .swal2-container .swal2-popup .swal2-footer {\n  border-color: #ebe9f1;\n}\n.dark-layout .swal2-container .swal2-popup .swal2-title {\n  color: #d0d2d6;\n}\n[dir] .dark-layout .swal2-container .swal2-modal {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-title {\n  color: #d0d2d6;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-icon-text {\n  color: inherit;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-left, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-right, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-fix, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-animate-success-icon {\n  background-color: #283046 !important;\n}\n.dark-layout .swal2-container .swal2-modal input.swal2-input {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal input.swal2-input {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-content,\n.dark-layout .swal2-container .swal2-modal .swal2-html-container {\n  color: #b4b7bd;\n}\n.dark-layout .swal2-container .swal2-modal pre,\n.dark-layout .swal2-container .swal2-modal code {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-input {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-footer {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-footer {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-validation-message {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-validation-message {\n  background-color: #161d31;\n}\nhtml[dir=rtl] .swal2-rtl .swal2-close {\n  left: 0;\n  right: auto;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../../../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
var ___CSS_LOADER_AT_RULE_IMPORT_0___ = __webpack_require__(/*! -!../../../../../../node_modules/css-loader/dist/cjs.js!sweetalert2/dist/sweetalert2.min.css */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sweetalert2/dist/sweetalert2.min.css");
exports = ___CSS_LOADER_API_IMPORT___(false);
exports.i(___CSS_LOADER_AT_RULE_IMPORT_0___);
// Module
exports.push([module.i, "/*\n$font-family-sans-serif: Public Sans,sans-serif,-apple-system,blinkmacsystemfont,Segoe UI,roboto,Helvetica Neue,arial,sans-serif,\"Apple Color Emoji\",\"Segoe UI Emoji\",Segoe UI Symbol !default;\n$font-family-monospace: Public Sans,sans-serif,-apple-system,blinkmacsystemfont,Segoe UI,roboto,Helvetica Neue,arial,sans-serif,\"Apple Color Emoji\",\"Segoe UI Emoji\",Segoe UI Symbol !default;\n*/\n[dir] .swal2-container .swal2-popup {\n  border-radius: 0.428rem;\n  padding-bottom: 1.5rem;\n}\n.swal2-container .swal2-popup .swal2-close:focus {\n  outline: none;\n}\n[dir] .swal2-container .swal2-popup .swal2-close:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup .swal2-content {\n  color: #6e6b7b;\n}\n.swal2-container .swal2-popup .swal2-title {\n  font-size: 1.5rem;\n  font-weight: inherit;\n  color: #5e5873;\n}\n[dir] .swal2-container .swal2-popup button.swal2-confirm:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup input.swal2-input {\n  height: 2.714rem;\n  font-size: 1rem;\n  color: #6e6b7b;\n  line-height: 1.45;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input {\n  padding: 0.438rem 1rem;\n  border-color: #d8d6de;\n  border-radius: 0.357rem;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input:focus {\n  border-color: #7367f0 !important;\n  box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step {\n  background-color: #7367f0;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step-line, [dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step {\n  background-color: #b3adf7;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step-line {\n  background-color: #7367f0;\n}\n.swal2-container .swal2-popup pre {\n  min-height: 1.35rem;\n}\n[dir] .swal2-container .swal2-popup pre {\n  margin-top: 0.5rem;\n}\n.swal2-container .swal2-popup .swal2-footer {\n  color: #6e6b7b;\n}\n[dir] .swal2-container .swal2-popup .swal2-footer {\n  border-color: #ebe9f1;\n}\n.dark-layout .swal2-container .swal2-popup .swal2-title {\n  color: #d0d2d6;\n}\n[dir] .dark-layout .swal2-container .swal2-modal {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-title {\n  color: #d0d2d6;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-icon-text {\n  color: inherit;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-left, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-right, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-fix, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-animate-success-icon {\n  background-color: #283046 !important;\n}\n.dark-layout .swal2-container .swal2-modal input.swal2-input {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal input.swal2-input {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-content,\n.dark-layout .swal2-container .swal2-modal .swal2-html-container {\n  color: #b4b7bd;\n}\n.dark-layout .swal2-container .swal2-modal pre,\n.dark-layout .swal2-container .swal2-modal code {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-input {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-footer {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-footer {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-validation-message {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-validation-message {\n  background-color: #161d31;\n}\nhtml[dir=rtl] .swal2-rtl .swal2-close {\n  left: 0;\n  right: auto;\n}", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc&":
/*!***********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-modal",
    {
      attrs: { title: "Input Nilai Magang", size: "xl" },
      on: { show: _vm.resetForm, hidden: _vm.hideModal, ok: _vm.handleOk },
      scopedSlots: _vm._u([
        {
          key: "modal-footer",
          fn: function (ref) {
            var ok = ref.ok
            var cancel = ref.cancel
            return [
              _c(
                "b-overlay",
                {
                  staticClass: "d-inline-block",
                  attrs: {
                    show: _vm.loading_form,
                    rounded: "",
                    opacity: "0.6",
                    "spinner-small": "",
                    "spinner-variant": "secondary",
                  },
                },
                [
                  _c(
                    "b-button",
                    {
                      on: {
                        click: function ($event) {
                          return cancel()
                        },
                      },
                    },
                    [_vm._v("Tutup")]
                  ),
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "b-overlay",
                {
                  staticClass: "d-inline-block",
                  attrs: {
                    show: _vm.loading_form,
                    rounded: "",
                    opacity: "0.6",
                    "spinner-small": "",
                    "spinner-variant": "success",
                  },
                },
                [
                  _c(
                    "b-button",
                    {
                      attrs: { variant: "success" },
                      on: {
                        click: function ($event) {
                          return ok()
                        },
                      },
                    },
                    [_vm._v("Simpan")]
                  ),
                ],
                1
              ),
            ]
          },
        },
      ]),
      model: {
        value: _vm.addModalShow,
        callback: function ($$v) {
          _vm.addModalShow = $$v
        },
        expression: "addModalShow",
      },
    },
    [
      _c(
        "b-overlay",
        {
          attrs: {
            show: _vm.loading_form,
            rounded: "",
            opacity: "0.6",
            size: "lg",
            "spinner-variant": "danger",
          },
        },
        [
          _c(
            "b-form",
            {
              ref: "form",
              on: {
                submit: function ($event) {
                  $event.stopPropagation()
                  $event.preventDefault()
                  return _vm.handleSubmit($event)
                },
              },
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { cols: "12" } },
                    [
                      _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Judul Magang",
                            "label-for": "pkl_id",
                            "label-cols-md": "3",
                            "invalid-feedback": _vm.feedback.pkl_id,
                            state: _vm.state.pkl_id,
                          },
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "pkl_id",
                              reduce: function (nama) {
                                return nama.pkl_id
                              },
                              label: "nama",
                              options: _vm.data_pkl,
                              placeholder: "== Pilih Judul Magang ==",
                              state: _vm.state.pkl_id,
                            },
                            on: { input: _vm.changePkl },
                            scopedSlots: _vm._u([
                              {
                                key: "no-options",
                                fn: function (ref) {
                                  var search = ref.search
                                  var searching = ref.searching
                                  var loading = ref.loading
                                  return [
                                    _vm._v(
                                      "\n                Tidak ada data untuk ditampilkan\n              "
                                    ),
                                  ]
                                },
                              },
                            ]),
                            model: {
                              value: _vm.form.pkl_id,
                              callback: function ($$v) {
                                _vm.$set(_vm.form, "pkl_id", $$v)
                              },
                              expression: "form.pkl_id",
                            },
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.loading_table
                    ? _c(
                        "b-col",
                        { staticClass: "text-center", attrs: { cols: "12" } },
                        [
                          _c("b-overlay", {
                            staticClass: "d-inline-block",
                            attrs: {
                              show: _vm.loading_table,
                              rounded: "",
                              opacity: "0.6",
                              "spinner-small": "",
                              "spinner-variant": "danger",
                            },
                          }),
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.data_siswa.length
                    ? _c(
                        "b-col",
                        { attrs: { cols: "12" } },
                        [
                          _vm.data_tp.length
                            ? [
                                _c(
                                  "b-table-simple",
                                  { attrs: { bordered: "", striped: "" } },
                                  [
                                    _c(
                                      "b-thead",
                                      [
                                        _c(
                                          "b-tr",
                                          [
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("No")]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("Nama")]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("NISN")]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("Tujuan Pembelajaran")]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("Nilai")]
                                            ),
                                            _vm._v(" "),
                                            _c(
                                              "b-th",
                                              { staticClass: "text-center" },
                                              [_vm._v("Deskripsi")]
                                            ),
                                          ],
                                          1
                                        ),
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    _c(
                                      "b-tbody",
                                      [
                                        _vm.data_siswa.length
                                          ? [
                                              _vm._l(
                                                _vm.data_siswa,
                                                function (siswa, index) {
                                                  return [
                                                    _c(
                                                      "b-tr",
                                                      [
                                                        _c(
                                                          "b-td",
                                                          {
                                                            staticClass:
                                                              "text-center",
                                                            staticStyle: {
                                                              "vertical-align":
                                                                "top",
                                                            },
                                                            attrs: {
                                                              rowspan:
                                                                _vm.data_tp
                                                                  .length + 2,
                                                            },
                                                          },
                                                          [
                                                            _vm._v(
                                                              _vm._s(index + 1)
                                                            ),
                                                          ]
                                                        ),
                                                        _vm._v(" "),
                                                        _c(
                                                          "b-td",
                                                          {
                                                            staticStyle: {
                                                              "vertical-align":
                                                                "top",
                                                            },
                                                            attrs: {
                                                              rowspan:
                                                                _vm.data_tp
                                                                  .length + 2,
                                                            },
                                                          },
                                                          [
                                                            _vm._v(
                                                              _vm._s(siswa.nama)
                                                            ),
                                                          ]
                                                        ),
                                                        _vm._v(" "),
                                                        _c(
                                                          "b-td",
                                                          {
                                                            staticClass:
                                                              "text-center",
                                                            staticStyle: {
                                                              "vertical-align":
                                                                "top",
                                                            },
                                                            attrs: {
                                                              rowspan:
                                                                _vm.data_tp
                                                                  .length + 2,
                                                            },
                                                          },
                                                          [
                                                            _vm._v(
                                                              _vm._s(siswa.nisn)
                                                            ),
                                                          ]
                                                        ),
                                                      ],
                                                      1
                                                    ),
                                                    _vm._v(" "),
                                                    _vm._l(
                                                      _vm.data_tp,
                                                      function (tp, index) {
                                                        return _vm.data_tp
                                                          .length
                                                          ? [
                                                              _c(
                                                                "b-tr",
                                                                [
                                                                  _c("b-td", [
                                                                    _vm._v(
                                                                      _vm._s(
                                                                        tp.deskripsi
                                                                      )
                                                                    ),
                                                                  ]),
                                                                  _vm._v(" "),
                                                                  _c(
                                                                    "b-td",
                                                                    [
                                                                      _c(
                                                                        "b-form-input",
                                                                        {
                                                                          attrs:
                                                                            {
                                                                              placeholder:
                                                                                "0",
                                                                            },
                                                                          model:
                                                                            {
                                                                              value:
                                                                                _vm
                                                                                  .form
                                                                                  .peserta_didik_id[
                                                                                  tp.tp_id +
                                                                                    "#" +
                                                                                    siswa.peserta_didik_id
                                                                                ],
                                                                              callback:
                                                                                function (
                                                                                  $$v
                                                                                ) {
                                                                                  _vm.$set(
                                                                                    _vm
                                                                                      .form
                                                                                      .peserta_didik_id,
                                                                                    tp.tp_id +
                                                                                      "#" +
                                                                                      siswa.peserta_didik_id,
                                                                                    $$v
                                                                                  )
                                                                                },
                                                                              expression:
                                                                                "form.peserta_didik_id[tp.tp_id+'#'+siswa.peserta_didik_id]",
                                                                            },
                                                                        }
                                                                      ),
                                                                    ],
                                                                    1
                                                                  ),
                                                                  _vm._v(" "),
                                                                  _c(
                                                                    "b-td",
                                                                    [
                                                                      _c(
                                                                        "b-form-textarea",
                                                                        {
                                                                          attrs:
                                                                            {
                                                                              placeholder:
                                                                                "ketik deskripsi disini...",
                                                                              rows: "3",
                                                                              "max-rows":
                                                                                "6",
                                                                            },
                                                                          model:
                                                                            {
                                                                              value:
                                                                                _vm
                                                                                  .form
                                                                                  .deskripsi[
                                                                                  tp.tp_id +
                                                                                    "#" +
                                                                                    siswa.peserta_didik_id
                                                                                ],
                                                                              callback:
                                                                                function (
                                                                                  $$v
                                                                                ) {
                                                                                  _vm.$set(
                                                                                    _vm
                                                                                      .form
                                                                                      .deskripsi,
                                                                                    tp.tp_id +
                                                                                      "#" +
                                                                                      siswa.peserta_didik_id,
                                                                                    $$v
                                                                                  )
                                                                                },
                                                                              expression:
                                                                                "form.deskripsi[tp.tp_id+'#'+siswa.peserta_didik_id]",
                                                                            },
                                                                        }
                                                                      ),
                                                                    ],
                                                                    1
                                                                  ),
                                                                ],
                                                                1
                                                              ),
                                                            ]
                                                          : _vm._e()
                                                      }
                                                    ),
                                                    _vm._v(" "),
                                                    _c(
                                                      "b-tr",
                                                      [
                                                        _c(
                                                          "b-td",
                                                          {
                                                            attrs: {
                                                              colspan: "3",
                                                            },
                                                          },
                                                          [
                                                            _c(
                                                              "b-form-textarea",
                                                              {
                                                                attrs: {
                                                                  placeholder:
                                                                    "ketik catatan magang disini...",
                                                                  rows: "3",
                                                                  "max-rows":
                                                                    "6",
                                                                },
                                                                model: {
                                                                  value:
                                                                    _vm.form
                                                                      .catatan[
                                                                      siswa
                                                                        .peserta_didik_id
                                                                    ],
                                                                  callback:
                                                                    function (
                                                                      $$v
                                                                    ) {
                                                                      _vm.$set(
                                                                        _vm.form
                                                                          .catatan,
                                                                        siswa.peserta_didik_id,
                                                                        $$v
                                                                      )
                                                                    },
                                                                  expression:
                                                                    "form.catatan[siswa.peserta_didik_id]",
                                                                },
                                                              }
                                                            ),
                                                          ],
                                                          1
                                                        ),
                                                      ],
                                                      1
                                                    ),
                                                  ]
                                                }
                                              ),
                                            ]
                                          : [
                                              _c(
                                                "b-tr",
                                                [
                                                  _c(
                                                    "b-td",
                                                    {
                                                      staticClass:
                                                        "text-center",
                                                      attrs: { colspan: "4" },
                                                    },
                                                    [
                                                      _vm._v(
                                                        "Tidak ada data untuk ditampilkan"
                                                      ),
                                                    ]
                                                  ),
                                                ],
                                                1
                                              ),
                                            ],
                                      ],
                                      2
                                    ),
                                  ],
                                  1
                                ),
                              ]
                            : [
                                _c(
                                  "h3",
                                  { staticClass: "text-center" },
                                  [
                                    _vm._v(
                                      "Tujuan Pembelajaran tidak ditemukan. Silahkan tambahkan dulu "
                                    ),
                                    _c(
                                      "b-link",
                                      {
                                        attrs: { to: { name: "magang-cp-tp" } },
                                      },
                                      [_vm._v("disini")]
                                    ),
                                  ],
                                  1
                                ),
                              ],
                        ],
                        2
                      )
                    : _vm._e(),
                ],
                1
              ),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87&":
/*!*************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-modal",
    {
      attrs: {
        title: "Detil Nilai Magang",
        size: "xl",
        "ok-only": "",
        "ok-title": "Tutup",
        "ok-variant": "secondary",
      },
      model: {
        value: _vm.detilModalShow,
        callback: function ($$v) {
          _vm.detilModalShow = $$v
        },
        expression: "detilModalShow",
      },
    },
    [
      _vm.detil_data
        ? _c(
            "b-table-simple",
            { attrs: { bordered: "", striped: "" } },
            [
              _c(
                "b-tbody",
                [
                  _c(
                    "b-tr",
                    [
                      _c("b-td", [_vm._v("Nama Pembimbing")]),
                      _vm._v(" "),
                      _c("b-td", [_vm._v(_vm._s(_vm.detil_data.guru.nama))]),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tr",
                    [
                      _c("b-td", [_vm._v("Nama DUDI")]),
                      _vm._v(" "),
                      _c("b-td", [_vm._v(_vm._s(_vm.detil_data.dudi.nama))]),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tr",
                    [
                      _c("b-td", [_vm._v("Nama Instruktur")]),
                      _vm._v(" "),
                      _c("b-td", [_vm._v(_vm._s(_vm.detil_data.instruktur))]),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tr",
                    [
                      _c("b-td", [_vm._v("Tanggal Mulai")]),
                      _vm._v(" "),
                      _c("b-td", [_vm._v(_vm._s(_vm.detil_data.tgl_mulai))]),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tr",
                    [
                      _c("b-td", [_vm._v("Tanggal Selesai")]),
                      _vm._v(" "),
                      _c("b-td", [_vm._v(_vm._s(_vm.detil_data.tgl_selesai))]),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-tr",
                    [
                      _c("b-td", { staticStyle: { "vertical-align": "top" } }, [
                        _vm._v("Anggota Magang"),
                      ]),
                      _vm._v(" "),
                      _c(
                        "b-td",
                        [
                          _c(
                            "b-table-simple",
                            { attrs: { bordered: "", striped: "" } },
                            [
                              _c(
                                "b-thead",
                                [
                                  _c(
                                    "b-tr",
                                    [
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("No")]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("Nama")]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("NISN")]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("Tujuan Pembelajaran")]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("Nilai")]
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "b-th",
                                        { staticClass: "text-center" },
                                        [_vm._v("Deskripsi")]
                                      ),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "b-tbody",
                                [
                                  _vm.data_siswa.length
                                    ? [
                                        _vm._l(
                                          _vm.data_siswa,
                                          function (siswa, index) {
                                            return [
                                              _c(
                                                "b-tr",
                                                [
                                                  _c(
                                                    "b-td",
                                                    {
                                                      staticClass:
                                                        "text-center",
                                                      staticStyle: {
                                                        "vertical-align": "top",
                                                      },
                                                      attrs: {
                                                        rowspan:
                                                          _vm.data_tp.length +
                                                          2,
                                                      },
                                                    },
                                                    [_vm._v(_vm._s(index + 1))]
                                                  ),
                                                  _vm._v(" "),
                                                  _c(
                                                    "b-td",
                                                    {
                                                      staticStyle: {
                                                        "vertical-align": "top",
                                                      },
                                                      attrs: {
                                                        rowspan:
                                                          _vm.data_tp.length +
                                                          2,
                                                      },
                                                    },
                                                    [_vm._v(_vm._s(siswa.nama))]
                                                  ),
                                                  _vm._v(" "),
                                                  _c(
                                                    "b-td",
                                                    {
                                                      staticClass:
                                                        "text-center",
                                                      staticStyle: {
                                                        "vertical-align": "top",
                                                      },
                                                      attrs: {
                                                        rowspan:
                                                          _vm.data_tp.length +
                                                          2,
                                                      },
                                                    },
                                                    [_vm._v(_vm._s(siswa.nisn))]
                                                  ),
                                                ],
                                                1
                                              ),
                                              _vm._v(" "),
                                              _vm._l(
                                                _vm.data_tp,
                                                function (tp, index) {
                                                  return _vm.data_tp.length
                                                    ? [
                                                        _c(
                                                          "b-tr",
                                                          [
                                                            _c("b-td", [
                                                              _vm._v(
                                                                _vm._s(
                                                                  tp.deskripsi
                                                                )
                                                              ),
                                                            ]),
                                                            _vm._v(" "),
                                                            _c(
                                                              "b-td",
                                                              {
                                                                staticClass:
                                                                  "text-center",
                                                              },
                                                              [
                                                                _vm._v(
                                                                  "\n                        " +
                                                                    _vm._s(
                                                                      _vm.getNilaiTp(
                                                                        tp.tp_id,
                                                                        siswa.nilai_pkl
                                                                      )
                                                                    ) +
                                                                    "\n                      "
                                                                ),
                                                              ]
                                                            ),
                                                            _vm._v(" "),
                                                            _c("b-td", [
                                                              _vm._v(
                                                                "\n                        " +
                                                                  _vm._s(
                                                                    _vm.getDeskTp(
                                                                      tp.tp_id,
                                                                      siswa.nilai_pkl
                                                                    )
                                                                  ) +
                                                                  "\n                      "
                                                              ),
                                                            ]),
                                                          ],
                                                          1
                                                        ),
                                                      ]
                                                    : _vm._e()
                                                }
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "b-tr",
                                                [
                                                  _c(
                                                    "b-td",
                                                    { attrs: { colspan: "3" } },
                                                    [
                                                      _vm._v(
                                                        "\n                      " +
                                                          _vm._s(
                                                            siswa.pd_pkl
                                                              ? siswa.pd_pkl
                                                                  .catatan
                                                              : "-"
                                                          ) +
                                                          "\n                    "
                                                      ),
                                                    ]
                                                  ),
                                                ],
                                                1
                                              ),
                                            ]
                                          }
                                        ),
                                      ]
                                    : [
                                        _c(
                                          "b-tr",
                                          [
                                            _c(
                                              "b-td",
                                              {
                                                staticClass: "text-center",
                                                attrs: { colspan: "6" },
                                              },
                                              [
                                                _vm._v(
                                                  "Tidak ada data untuk ditampilkan"
                                                ),
                                              ]
                                            ),
                                          ],
                                          1
                                        ),
                                      ],
                                ],
                                2
                              ),
                            ],
                            1
                          ),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                ],
                1
              ),
            ],
            1
          )
        : _vm._e(),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466&":
/*!*******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466& ***!
  \*******************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "b-row",
        [
          _c(
            "b-col",
            { staticClass: "mb-2", attrs: { md: "4" } },
            [
              _c("v-select", {
                attrs: {
                  options: [10, 25, 50, 100],
                  clearable: false,
                  searchable: false,
                },
                on: { input: _vm.loadPerPage },
                model: {
                  value: _vm.meta.per_page,
                  callback: function ($$v) {
                    _vm.$set(_vm.meta, "per_page", $$v)
                  },
                  expression: "meta.per_page",
                },
              }),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-col",
            { staticClass: "mb-2", attrs: { md: "4", "offset-md": "4" } },
            [
              _c("b-form-input", {
                attrs: { placeholder: "Cari data..." },
                on: { input: _vm.search },
              }),
            ],
            1
          ),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-overlay",
        {
          attrs: {
            show: _vm.loading,
            rounded: "",
            opacity: "0.6",
            size: "lg",
            "spinner-variant": "warning",
          },
        },
        [
          _c("b-table", {
            attrs: {
              bordered: "",
              striped: "",
              items: _vm.items,
              fields: _vm.fields,
              "sort-by": _vm.sortBy,
              "sort-desc": _vm.sortDesc,
              "show-empty": "",
              busy: _vm.isBusy,
            },
            on: {
              "update:sortBy": function ($event) {
                _vm.sortBy = $event
              },
              "update:sort-by": function ($event) {
                _vm.sortBy = $event
              },
              "update:sortDesc": function ($event) {
                _vm.sortDesc = $event
              },
              "update:sort-desc": function ($event) {
                _vm.sortDesc = $event
              },
            },
            scopedSlots: _vm._u([
              {
                key: "empty",
                fn: function (scope) {
                  return [
                    _c("p", { staticClass: "text-center" }, [
                      _vm._v("Tidak ada data untuk ditampilkan"),
                    ]),
                  ]
                },
              },
              {
                key: "table-busy",
                fn: function () {
                  return [
                    _c(
                      "div",
                      { staticClass: "text-center text-danger my-2" },
                      [
                        _c("b-spinner", { staticClass: "align-middle" }),
                        _vm._v(" "),
                        _c("strong", [_vm._v("Loading...")]),
                      ],
                      1
                    ),
                  ]
                },
                proxy: true,
              },
              {
                key: "cell(guru)",
                fn: function (row) {
                  return [
                    _vm._v(
                      "\n        " + _vm._s(row.item.guru.nama) + "\n      "
                    ),
                  ]
                },
              },
              {
                key: "cell(dudi)",
                fn: function (row) {
                  return [
                    _vm._v(
                      "\n        " + _vm._s(row.item.dudi.nama) + "\n      "
                    ),
                  ]
                },
              },
              {
                key: "cell(rombongan_belajar)",
                fn: function (row) {
                  return [
                    _vm._v(
                      "\n        " +
                        _vm._s(row.item.rombongan_belajar.nama) +
                        "\n      "
                    ),
                  ]
                },
              },
              {
                key: "cell(actions)",
                fn: function (row) {
                  return [
                    _c(
                      "b-button",
                      {
                        attrs: { variant: "primary", size: "sm" },
                        on: {
                          click: function ($event) {
                            return _vm.aksi(row.item, "detil")
                          },
                        },
                      },
                      [_vm._v(" Detil")]
                    ),
                  ]
                },
              },
            ]),
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-row",
        { staticClass: "mt-2" },
        [
          _c("b-col", { attrs: { md: "6" } }, [
            _c("p", [
              _vm._v(
                "Menampilkan " +
                  _vm._s(_vm.meta.from ? _vm.meta.from : 0) +
                  " sampai " +
                  _vm._s(_vm.meta.to) +
                  " dari " +
                  _vm._s(_vm.meta.total) +
                  " entri"
              ),
            ]),
          ]),
          _vm._v(" "),
          _c(
            "b-col",
            { attrs: { md: "6" } },
            [
              _c("b-pagination", {
                attrs: {
                  "total-rows": _vm.meta.total,
                  "per-page": _vm.meta.per_page,
                  align: "right",
                  "aria-controls": "dw-datatable",
                },
                on: { change: _vm.changePage },
                model: {
                  value: _vm.meta.current_page,
                  callback: function ($$v) {
                    _vm.$set(_vm.meta, "current_page", $$v)
                  },
                  expression: "meta.current_page",
                },
              }),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-card",
    { attrs: { "no-body": "" } },
    [
      _c("b-card-body", [
        _vm.isBusy
          ? _c(
              "div",
              { staticClass: "text-center text-danger my-2" },
              [
                _c("b-spinner", { staticClass: "align-middle" }),
                _vm._v(" "),
                _c("strong", [_vm._v("Loading...")]),
              ],
              1
            )
          : _c(
              "div",
              [
                _c("datatable", {
                  attrs: {
                    loading: _vm.loading,
                    isBusy: _vm.isBusy,
                    items: _vm.items,
                    fields: _vm.fields,
                    meta: _vm.meta,
                  },
                  on: {
                    per_page: _vm.handlePerPage,
                    pagination: _vm.handlePagination,
                    search: _vm.handleSearch,
                    sort: _vm.handleSort,
                    aksi: _vm.handleAksi,
                  },
                }),
              ],
              1
            ),
      ]),
      _vm._v(" "),
      _c("add-modal", { on: { reload: _vm.handleReload } }),
      _vm._v(" "),
      _c("detil-modal", { on: { stop_loading: _vm.handleLoading } }),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../../../node_modules/css-loader/dist/cjs.js!../../../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& */ "./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& */ "./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./resources/js/src/@core/utils/eventBus.js":
/*!**************************************************!*\
  !*** ./resources/js/src/@core/utils/eventBus.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);

var eventBus = new vue__WEBPACK_IMPORTED_MODULE_0___default.a();
/* harmony default export */ __webpack_exports__["default"] = (eventBus);

/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue":
/*!*********************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AddModal.vue?vue&type=template&id=7ceb23bc& */ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc&");
/* harmony import */ var _AddModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AddModal.vue?vue&type=script&lang=js& */ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& */ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AddModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddModal.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&":
/*!*******************************************************************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/style-loader!../../../../../../../../node_modules/css-loader/dist/cjs.js!../../../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=style&index=0&id=7ceb23bc&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_style_index_0_id_7ceb23bc_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc& ***!
  \****************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AddModal.vue?vue&type=template&id=7ceb23bc& */ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/AddModal.vue?vue&type=template&id=7ceb23bc&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AddModal_vue_vue_type_template_id_7ceb23bc___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue":
/*!***********************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DetilModal.vue?vue&type=template&id=7561fd87& */ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87&");
/* harmony import */ var _DetilModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./DetilModal.vue?vue&type=script&lang=js& */ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _DetilModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__["render"],
  _DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DetilModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./DetilModal.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DetilModal_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87&":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87& ***!
  \******************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./DetilModal.vue?vue&type=template&id=7561fd87& */ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/components/modal/magang/input-nilai/DetilModal.vue?vue&type=template&id=7561fd87&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_DetilModal_vue_vue_type_template_id_7561fd87___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Datatable.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Datatable.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Datatable.vue?vue&type=template&id=4c181466& */ "./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466&");
/* harmony import */ var _Datatable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Datatable.vue?vue&type=script&lang=js& */ "./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Datatable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/magang/input-nilai/Datatable.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Datatable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Datatable.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Datatable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466&":
/*!************************************************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466& ***!
  \************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Datatable.vue?vue&type=template&id=4c181466& */ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Datatable.vue?vue&type=template&id=4c181466&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Datatable_vue_vue_type_template_id_4c181466___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Index.vue":
/*!*************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Index.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=1357c1bb& */ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& */ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/magang/input-nilai/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& ***!
  \***********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=style&index=0&id=1357c1bb&lang=scss&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_style_index_0_id_1357c1bb_lang_scss___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb&":
/*!********************************************************************************************!*\
  !*** ./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=1357c1bb& */ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/magang/input-nilai/Index.vue?vue&type=template&id=1357c1bb&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_1357c1bb___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);