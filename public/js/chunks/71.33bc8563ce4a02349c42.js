(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[71],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.filter.js */ "./node_modules/core-js/modules/es.array.filter.js");
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.reduce.js */ "./node_modules/core-js/modules/es.array.reduce.js");
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_4__);



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
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCol"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCard"],
    BCardBody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCardBody"],
    BOverlay: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BOverlay"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BFormGroup"],
    BTableSimple: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTableSimple"],
    BThead: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BThead"],
    BTbody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTbody"],
    BTr: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTr"],
    BTh: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTh"],
    BTd: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BTd"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BButton"],
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_4___default.a
  },
  data: function data() {
    return {
      isBusy: false,
      show: false,
      loading_rombel: false,
      data_tingkat: [{
        id: 10,
        nama: 'Kelas 10'
      }, {
        id: 11,
        nama: 'Kelas 11'
      }, {
        id: 12,
        nama: 'Kelas 12'
      }, {
        id: 13,
        nama: 'Kelas 13'
      }],
      data_rombel: [],
      data_siswa: [],
      data_pembelajaran: [],
      form: {
        semester_id: '',
        legger: true,
        tingkat: '',
        rombongan_belajar_id: ''
      },
      feedback: {
        tingkat: '',
        rombongan_belajar_id: ''
      },
      state: {
        tingkat: null,
        rombongan_belajar_id: null
      }
    };
  },
  created: function created() {
    this.form.semester_id = this.user.semester.semester_id;
  },
  methods: {
    changeTingkat: function changeTingkat(val) {
      var _this = this;
      this.show = false;
      this.form.rombongan_belajar_id = '';
      if (val) {
        this.loading_rombel = true;
        this.$http.post('/referensi/get-rombel', this.form).then(function (response) {
          _this.loading_rombel = false;
          _this.data_rombel = response.data;
        });
      }
    },
    changeRombel: function changeRombel(val) {
      this.show = false;
      if (val) {
        this.loading_mapel = true;
        this.loadPostsData();
      }
    },
    loadPostsData: function loadPostsData(val) {
      var _this2 = this;
      this.isBusy = true;
      this.$http.post('/monitoring/legger', this.form).then(function (response) {
        _this2.show = true;
        _this2.isBusy = false;
        var getData = response.data;
        _this2.data_siswa = getData.pd;
        _this2.data_pembelajaran = getData.pembelajaran;
      });
    },
    getNilai: function getNilai(all_nilai, anggota_rombel_id) {
      var arr = all_nilai.filter(function (item) {
        return item.anggota_rombel_id === anggota_rombel_id;
      });
      var length = arr.length;
      return arr.reduce(function (acc, val) {
        return acc + val.angka / length;
      }, 0);
    },
    unduhLegger: function unduhLegger() {
      window.open("/export/legger/".concat(this.form.rombongan_belajar_id), '_blank');
    }
  }
});

/***/ }),

/***/ "./node_modules/core-js/internals/array-reduce.js":
/*!********************************************************!*\
  !*** ./node_modules/core-js/internals/array-reduce.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var aCallable = __webpack_require__(/*! ../internals/a-callable */ "./node_modules/core-js/internals/a-callable.js");
var toObject = __webpack_require__(/*! ../internals/to-object */ "./node_modules/core-js/internals/to-object.js");
var IndexedObject = __webpack_require__(/*! ../internals/indexed-object */ "./node_modules/core-js/internals/indexed-object.js");
var lengthOfArrayLike = __webpack_require__(/*! ../internals/length-of-array-like */ "./node_modules/core-js/internals/length-of-array-like.js");

var $TypeError = TypeError;

// `Array.prototype.{ reduce, reduceRight }` methods implementation
var createMethod = function (IS_RIGHT) {
  return function (that, callbackfn, argumentsLength, memo) {
    aCallable(callbackfn);
    var O = toObject(that);
    var self = IndexedObject(O);
    var length = lengthOfArrayLike(O);
    var index = IS_RIGHT ? length - 1 : 0;
    var i = IS_RIGHT ? -1 : 1;
    if (argumentsLength < 2) while (true) {
      if (index in self) {
        memo = self[index];
        index += i;
        break;
      }
      index += i;
      if (IS_RIGHT ? index < 0 : length <= index) {
        throw $TypeError('Reduce of empty array with no initial value');
      }
    }
    for (;IS_RIGHT ? index >= 0 : length > index; index += i) if (index in self) {
      memo = callbackfn(memo, self[index], index, O);
    }
    return memo;
  };
};

module.exports = {
  // `Array.prototype.reduce` method
  // https://tc39.es/ecma262/#sec-array.prototype.reduce
  left: createMethod(false),
  // `Array.prototype.reduceRight` method
  // https://tc39.es/ecma262/#sec-array.prototype.reduceright
  right: createMethod(true)
};


/***/ }),

/***/ "./node_modules/core-js/internals/engine-is-node.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/internals/engine-is-node.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {
var classof = __webpack_require__(/*! ../internals/classof-raw */ "./node_modules/core-js/internals/classof-raw.js");

module.exports = typeof process != 'undefined' && classof(process) == 'process';

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../process/browser.js */ "./node_modules/process/browser.js")))

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

/***/ "./node_modules/core-js/modules/es.array.reduce.js":
/*!*********************************************************!*\
  !*** ./node_modules/core-js/modules/es.array.reduce.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var $reduce = __webpack_require__(/*! ../internals/array-reduce */ "./node_modules/core-js/internals/array-reduce.js").left;
var arrayMethodIsStrict = __webpack_require__(/*! ../internals/array-method-is-strict */ "./node_modules/core-js/internals/array-method-is-strict.js");
var CHROME_VERSION = __webpack_require__(/*! ../internals/engine-v8-version */ "./node_modules/core-js/internals/engine-v8-version.js");
var IS_NODE = __webpack_require__(/*! ../internals/engine-is-node */ "./node_modules/core-js/internals/engine-is-node.js");

// Chrome 80-82 has a critical bug
// https://bugs.chromium.org/p/chromium/issues/detail?id=1049982
var CHROME_BUG = !IS_NODE && CHROME_VERSION > 79 && CHROME_VERSION < 83;
var FORCED = CHROME_BUG || !arrayMethodIsStrict('reduce');

// `Array.prototype.reduce` method
// https://tc39.es/ecma262/#sec-array.prototype.reduce
$({ target: 'Array', proto: true, forced: FORCED }, {
  reduce: function reduce(callbackfn /* , initialValue */) {
    var length = arguments.length;
    return $reduce(this, callbackfn, length, length > 1 ? arguments[1] : undefined);
  }
});


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33& ***!
  \********************************************************************************************************************************************************************************************************************************************/
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
      _c(
        "b-card-body",
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
                        label: "Tingkat Kelas",
                        "label-for": "tingkat",
                        "label-cols-md": "3",
                        "invalid-feedback": _vm.feedback.tingkat,
                        state: _vm.state.tingkat,
                      },
                    },
                    [
                      _c("v-select", {
                        attrs: {
                          id: "tingkat",
                          reduce: function (nama) {
                            return nama.id
                          },
                          label: "nama",
                          options: _vm.data_tingkat,
                          placeholder: "== Pilih Tingkat Kelas ==",
                          searchable: false,
                          state: _vm.state.tingkat,
                        },
                        on: { input: _vm.changeTingkat },
                        scopedSlots: _vm._u([
                          {
                            key: "no-options",
                            fn: function (ref) {
                              var search = ref.search
                              var searching = ref.searching
                              var loading = ref.loading
                              return [
                                _vm._v(
                                  "\n              Tidak ada data untuk ditampilkan\n            "
                                ),
                              ]
                            },
                          },
                        ]),
                        model: {
                          value: _vm.form.tingkat,
                          callback: function ($$v) {
                            _vm.$set(_vm.form, "tingkat", $$v)
                          },
                          expression: "form.tingkat",
                        },
                      }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-form-group",
                    {
                      attrs: {
                        label: "Kelas",
                        "label-for": "rombongan_belajar_id",
                        "label-cols-md": "3",
                        "invalid-feedback": _vm.feedback.rombongan_belajar_id,
                        state: _vm.state.rombongan_belajar_id,
                      },
                    },
                    [
                      _c(
                        "b-overlay",
                        {
                          attrs: {
                            show: _vm.loading_rombel,
                            opacity: "0.6",
                            size: "md",
                            "spinner-variant": "secondary",
                          },
                        },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "tingkat",
                              reduce: function (nama) {
                                return nama.rombongan_belajar_id
                              },
                              label: "nama",
                              options: _vm.data_rombel,
                              placeholder: "== Pilih Kelas ==",
                              state: _vm.state.rombongan_belajar_id,
                            },
                            on: { input: _vm.changeRombel },
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
                              value: _vm.form.rombongan_belajar_id,
                              callback: function ($$v) {
                                _vm.$set(_vm.form, "rombongan_belajar_id", $$v)
                              },
                              expression: "form.rombongan_belajar_id",
                            },
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _vm.show
                    ? _c(
                        "b-form-group",
                        {
                          attrs: {
                            label: "Unduh Legger",
                            "label-for": "rombongan_belajar_id",
                            "label-cols-md": "3",
                          },
                        },
                        [
                          _c(
                            "b-button",
                            {
                              attrs: { variant: "danger" },
                              on: { click: _vm.unduhLegger },
                            },
                            [_vm._v("Unduh Legger")]
                          ),
                        ],
                        1
                      )
                    : _vm._e(),
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "b-col",
                { attrs: { cols: "12" } },
                [
                  _vm.show
                    ? _c(
                        "b-table-simple",
                        { attrs: { responsive: "", bordered: "" } },
                        [
                          _c(
                            "b-thead",
                            [
                              _c(
                                "b-tr",
                                [
                                  _c("b-th", { staticClass: "text-center" }, [
                                    _vm._v("No"),
                                  ]),
                                  _vm._v(" "),
                                  _c("b-th", { staticClass: "text-center" }, [
                                    _vm._v("Nama Siswa"),
                                  ]),
                                  _vm._v(" "),
                                  _c("b-th", { staticClass: "text-center" }, [
                                    _vm._v("NISN"),
                                  ]),
                                  _vm._v(" "),
                                  _vm._l(
                                    _vm.data_pembelajaran,
                                    function (pembelajaran, index) {
                                      return _c(
                                        "b-th",
                                        {
                                          key: pembelajaran.pembelajaran_id,
                                          staticClass: "text-center",
                                        },
                                        [
                                          _vm._v(
                                            _vm._s(
                                              pembelajaran.nama_mata_pelajaran
                                            )
                                          ),
                                        ]
                                      )
                                    }
                                  ),
                                ],
                                2
                              ),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "b-tbody",
                            [
                              _vm._l(_vm.data_siswa, function (siswa, index) {
                                return _vm.data_siswa.length
                                  ? [
                                      _c(
                                        "b-tr",
                                        [
                                          _c(
                                            "b-td",
                                            { staticClass: "text-center" },
                                            [_vm._v(_vm._s(index + 1))]
                                          ),
                                          _vm._v(" "),
                                          _c("b-td", [
                                            _vm._v(_vm._s(siswa.nama)),
                                          ]),
                                          _vm._v(" "),
                                          _c(
                                            "b-td",
                                            { staticClass: "text-center" },
                                            [_vm._v(_vm._s(siswa.nisn))]
                                          ),
                                          _vm._v(" "),
                                          _vm._l(
                                            _vm.data_pembelajaran,
                                            function (pembelajaran, index) {
                                              return _c(
                                                "b-td",
                                                {
                                                  key: pembelajaran.pembelajaran_id,
                                                  staticClass: "text-center",
                                                },
                                                [
                                                  _vm._v(
                                                    _vm._s(
                                                      Math.ceil(
                                                        _vm.getNilai(
                                                          pembelajaran.nilai,
                                                          siswa.anggota_rombel
                                                            .anggota_rombel_id
                                                        )
                                                      )
                                                    )
                                                  ),
                                                ]
                                              )
                                            }
                                          ),
                                        ],
                                        2
                                      ),
                                    ]
                                  : _vm._e()
                              }),
                            ],
                            2
                          ),
                        ],
                        1
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

/***/ "./resources/js/src/views/monitoring/Legger.vue":
/*!******************************************************!*\
  !*** ./resources/js/src/views/monitoring/Legger.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Legger.vue?vue&type=template&id=56bb1a33& */ "./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33&");
/* harmony import */ var _Legger_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Legger.vue?vue&type=script&lang=js& */ "./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Legger_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/monitoring/Legger.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Legger_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Legger.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/monitoring/Legger.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Legger_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33&":
/*!*************************************************************************************!*\
  !*** ./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Legger.vue?vue&type=template&id=56bb1a33& */ "./node_modules/laravel-mix/node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/monitoring/Legger.vue?vue&type=template&id=56bb1a33&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_laravel_mix_node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Legger_vue_vue_type_template_id_56bb1a33___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);