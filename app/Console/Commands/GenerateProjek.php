<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tema_projek;
use App\Models\Opsi_projek;
use App\Models\Elemen_projek;
use App\Models\Dimensi_projek;

class GenerateProjek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:projek';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [
            [
                'id' => 1,
                'kode' => 'BB',
                'nama' => 'Belum Berkembang',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'yellow',
            ],
            [
                'id' => 2,
                'kode' => 'MB',
                'nama' => 'Mulai Berkembang',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'yellow',
            ],
            [
                'id' => 3,
                'kode' => 'BSH',
                'nama' => 'Berkembang Sesuai Harapan',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'red',
            ],
            [
                'id' => 4,
                'kode' => 'SB',
                'nama' => 'Sedang Berkembang',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'blue',
            ],
            [
                'id' => 5,
                'kode' => 'SAB',
                'nama' => 'Sangat Berkembang',
                'deskripsi' => 'Peserta Didik masih membutuhkan bimbingan dalam mengembangkan kemampuan',
                'warna' => 'green',
            ],
        ];
        foreach($data as $d){
            Opsi_projek::updateOrCreate(
                [
                    'kode' => $d['kode'],
                ],
                [
                    'nama' => $d['nama'],
                    'deskripsi' => $d['deskripsi'],
                    'warna' => $d['warna'],
                ]
            );
        }
        $data = [
            'Gaya Hidup Berkelanjutan',
            'Kearifan Lokal',
            'Bhineka Tunggal Ika',
            'Bangunlah Jiwa dan Raganya',
            'Suara Demokrasi',
            'Rekayasa dan Teknologi',
            'Kebekerjaan',
        ];
        foreach($data as $d){
            Tema_projek::updateOrCreate([
                'nama' => $d,
            ]);
        }
        $data = [
            [
                'nama' => 'Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia',
                'elemen' => [
                    [
                        'nama' => 'Akhlak beragama',
                        'elemen' => [
                            [
                                'nama' => 'Melaksanakan ibadah secara rutin dan mandiri serta menyadari arti penting ibadah tersebut dan berpartisipasi aktif pada kegiatan keagamaan atau kepercayaan.'
                            ],
                            [
                                'nama' => 'Melakukan perbuatan baik sesuai tuntunan ajaran agama secara sadar dan berulang.'
                            ],
                            [
                                'nama' => 'Memahami struktur organisasi, unsur-unsur utama agama/kepercayaan dalam konteks Indonesia, memahami kontribusi agama/kepercayaan terhadap peradaban dunia.'
                            ],
                            [
                                'nama' => 'Menerapkan pemahamannya tentang kualitas atau sifat-sifat Tuhan dalam ritual ibadahnya baik ibadah yang bersifat personal maupun sosial.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Akhlak pribadi',
                        'elemen' => [
                            [
                                'nama' => 'Berkata yang baik dan jujur, menjaga amanah dengan baik, konsisten, serta menjauhkan diri dari perbuatan yang kurang baik.',
                            ],
                            [
                                'nama' => 'Melakukan aktivitas fisik, sosial, dan ibadah secara seimbang.',
                            ],
                            [
                                'nama' => 'Menyadari bahwa aturan agama dan sosial merupakan aturan yang baik dan menjadi bagian dari diri sehingga bisa menerapkannya secara bijak dan kontekstual.',
                            ],
                        ]
                    ],
                    [
                        'nama' => 'Akhlak kepada manusia',
                        'elemen' => [
                            [
                                'nama' => 'Melakukan perbuatan baik kepada orang lain.',
                            ],
                            [
                                'nama' => 'Memahami dan menghargai perasaan dan sudut pandang orang dan/atau kelompok lain.',
                            ],
                            [
                                'nama' => 'Mengidentifikasi hal yang menjadi permasalahan bersama, memberikan alternatif solusi untuk menjembatani perbedaan dengan mengutamakan kemanusiaan.',
                            ],
                        ]
                    ],
                    [
                        'nama' => 'Akhlak kepada alam',
                        'elemen' => [
                            [
                                'nama' => 'Memelihara alam.',
                            ],
                            [
                                'nama' => 'Mengidentifikasi masalah lingkungan hidup di tempat ia tinggal dan melakukan langkah-langkah konkret yang bisa dilakukan untuk menghindari kerusakan dan menjaga keharmonisan ekosistem yang ada di lingkungannya.',
                            ],
                            [
                                'nama' => 'Mewujudkan rasa syukur dengan membangun kesadaran peduli lingkungan alam dengan menciptakan dan mengimplementasikan solusi dari permasalahan lingkungan yang ada.',
                            ]
                        ]
                    ],
                    [
                        'nama' => 'Akhlak bernegara',
                        'elemen' => [
                            [
                                'nama' => 'Mematuhi peraturan perundangan yang berlaku.',
                            ],
                            [
                                'nama' => 'Menggunakan hak dan melaksanakan kewajiban kewarganegaraan dan terbiasa mendahulukan kepentingan umum di atas kepentingan pribadi sebagai wujud dari keimanannya kepada Tuhan YME.',
                            ]
                        ]
                    ],
                ],
            ],
            [
                'nama' => 'Bernalar Kritis',
                'elemen' => [
                    [
                        'nama' => 'Memperoleh dan memproses informasi dan gagasan',
                        'elemen' => [
                            [
                                'nama' => 'Mengajukan pertanyaan untuk menganalisis secara kritis permasalahan yang kompleks dan abstrak.'
                            ],
                            [
                                'nama' => 'Secara kritis mengklarifikasi serta menganalisis gagasan dan informasi yang kompleks dan abstrak dari berbagai sumber. Memprioritaskan suatu gagasan yang paling relevan dari hasil klarifikasi dan analisis.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Menganalisis dan mengevaluasi penalaran',
                        'elemen' => [
                            [
                                'nama' => 'Menganalisis dan mengevaluasi penalaran yang digunakannya dalam menemukan dan mencari solusi serta mengambil keputusan.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Menganalisis dan mengevaluasi penalaran dan prosedurnya',
                        'elemen' => [
                            [
                                'nama' => 'Menganalisis dan mengevaluasi penalaran yang digunakannya dalam menemukan dan mencari solusi serta mengambil keputusan.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Mengidentifikasi, mengklarifikasi, dan mengolah informasi dan gagasan',
                        'elemen' => [
                            [
                                'nama' => 'Secara kritis mengklarifikasi serta menganalisis gagasan dan informasi yang kompleks dan abstrak dari berbagai sumber. Memprioritaskan suatu gagasan yang paling relevan dari hasil klarifikasi dan analisis.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Merefleksi dan mengevaluasi pemilirannya sendiri',
                        'elemen' => [
                            [
                                'nama' => 'Menjelaskan alasan untuk mendukung pemikirannya dan memikirkan pandangan yang mungkin berlawanan dengan pemikirannya dan mengubah pemikirannya jika diperlukan.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Refleksi pemikiran dan proses berpikir',
                        'elemen' => [
                            [
                                'nama' => 'Menjelaskan alasan untuk mendukung pemikirannya dan memikirkan pandangan yang mungkin berlawanan dengan pemikirannya dan mengubah pemikirannya jika diperlukan.'
                            ]
                        ],
                    ],
                ],
            ],
            [
                'nama' => 'Mandiri',
                'elemen' => [
                    [
                        'nama' => 'Pemahaman diri dan situasi',
                        'elemen' => [
                            [
                                'nama' => 'Melakukan refleksi terhadap umpan balik dari teman, guru, dan orang dewasa lainnya, serta informasi-informasi karir yang akan dipilihnya untuk menganalisis karakteristik dan keterampilan yang dibutuhkan dalam menunjang atau menghambat karirnya di masa depan.'
                            ],
                            [
                                'nama' => 'Mempunyai kemampuan dalam membaca keadaan diri dalam menghadapi tantangan yang ada serta mencari pemecahan tantangan  berdasarkan situasi yang ada.'
                            ],
                            [
                                'nama' => 'Mengidentifikasi kekuatan dan tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya dimasa depan.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Regulasi diri',
                        'elemen' => [
                            [
                                'nama' => 'Melakukan tindakan-tindakan secara konsisten guna mencapai tujuan karirdan pengembangan dirinya di masa depan, serta berusaha mencari dan melakukan alternatif tindakan lain yang dapat dilakukan ketika menemui hambatan.'
                            ],
                            [
                                'nama' => 'Mempunyai standar dalam mengatur diri sendiri dan menjalankan kewajiban diri dengan tetap menghormati hak-hak orang lain.'
                            ],
                            [
                                'nama' => 'Menentukan prioritas pribadi, berinisiatif mencari dan mengembangkan pengetahuan dan keterampilan yang spesifik sesuai tujuan di masa depan.'
                            ],
                            [
                                'nama' => 'Mengendalikan dan menyesuaikan emosi yang dirasakannya secara tepat ketika menghadapi situasi yang menantang dan menekan pada konteks belajar, relasi, dan pekerjaan.'
                            ],
                            [
                                'nama' => 'Mengevaluasi efektivitas strategi pembelajaran digunakannya, serta menetapkan tujuan belajar, prestasi, dan pengembangan diri secara spesifik dan merancang strategi yang sesuai untuk menghadapi tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya di masa depan.'
                            ],
                            [
                                'nama' => 'Menyesuaikan dan mulai menjalankan rencana dan strategi pengembangan dirinya dengan mempertimbangkan minat dan tuntutan pada konteks belajar maupun pekerjaan yang akan dijalaninya di masa depan, serta berusaha untuk mengatasi tantangan-tantangan yang ditemui.'
                            ]
                        ],
                    ]
                ],
            ],
            [
                'nama' => 'Berkebinekaan Global',
                'elemen' => [
                    [
                        'nama' => 'Berkeadilan sosial',
                        'elemen' => [
                            [
                                'nama' => 'Berinisiatif melakukan suatu tindakan berdasarkan identifikasi masalah untuk mempromosikan keadilan, keamanan ekonomi, menopang ekologi dan demokrasi sambil menghindari kerugian jangka panjang terhadap manusia, alam ataupun masyarakat.'
                            ],
                            [
                                'nama' => 'Berpartisipasi menentukan pilihan dan keputusan untuk kepentingan bersama melalui proses bertukar pikiran secara cermat dan terbuka secara mandiri.'
                            ],
                            [
                                'nama' => 'Kemampuan dalam bersiap adil terhadap orang-orang yang berbeda latar belakang.'
                            ],
                            [
                                'nama' => 'Memahami konsep hak dan kewajiban, serta implikasinya terhadap ekspresi dan perilakunya. Mulai mencari solusi untuk dilema terkait konsep hak dan kewajibannya.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Komunikasi dan interaksi antar budaya',
                        'elemen' => [
                            [
                                'nama' => 'Kemampuan dalam menjalin hubungan melalui berbagai macam bentuk komunikasi dan interaksi dengan orang lain yang mempunyai budaya dan latar belakang yang berbeda.'
                            ],
                            [
                                'nama' => 'Menganalisis hubungan antara bahasa, pikiran, dan konteks untukmemahami dan meningkatkan komunikasi antarbudaya yangberbeda-beda.'
                            ],
                            [
                                'nama' => 'Menyajikan pandangan yang seimbang mengenai permasalahan yang dapat menimbulkan pertentangan pendapat, memosisikan orang lain dan budaya yang berbeda darinya secara setara, serta bersedia memberikan pertolongan ketika orang lain berada dalam situasi sulit.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Mengenal dan benghargai budaya',
                        'elemen' => [
                            [
                                'nama' => 'Keinginan untuk mengetahui budaya lain dan membangun rasa menghargai terhadap kebudayaan yang berbeda tersebut.'
                            ],
                            [
                                'nama' => 'Mempromosikan pertukaran budaya dan kolaborasi dalam dunia yang saling terhubung serta menunjukkannya dalam perilaku.'
                            ],
                            [
                                'nama' => 'Menganalisis dinamika budaya yang mencakup pemahaman, kepercayaan, dan praktik keseharian dalam rentang waktu yang panjang dan konteks yang luas.'
                            ],
                            [
                                'nama' => 'Menganalisis pengaruh keanggotaan kelompok lokal, regional, nasional, dan global terhadap pembentukan identitas, termasuk identitas dirinya. Mulai menginternalisasi identitas diri sebagai bagian dari budaya bangsa.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Refleksi dan tanggungjawab terhadap kebinekaan',
                        'elemen' => [
                            [
                                'nama' => 'Keinginan untuk menjadikan pengalaman bertemu orang yang berbeda sebagai satu cara untuk membangun persahabatan dan pertemanan yang berdasarkan pada nilai-nilai kemanusiaan'
                            ],
                            [
                                'nama' => 'Mengetahui tantangan dan keuntungan hidup dalam lingkungan dengan budaya yang beragam, serta memahami pentingnya kerukunan antar budaya dalam kehidupan bersama yang harmonis.'
                            ],
                            [
                                'nama' => 'Mengkritik dan menolak stereotip serta prasangka tentang gambaran identitas kelompok dan suku bangsa serta berinisiatif mengajak orang lain untuk menolak stereotip dan prasangka.'
                            ],
                            [
                                'nama' => 'Merefleksikan secara kritis dampak dari pengalaman hidup di lingkungan yang beragam terkait dengan perilaku, kepercayaan serta tindakannya terhadap orang lain.'
                            ]
                        ],
                    ],
                ],
            ],
            [
                'nama' => 'Perkembangan Dimensi Kreatif',
                'elemen' => [
                    [
                        'nama' => 'Melahirkan karya dan tindakan yang orisinal',
                        'elemen' => [
                            [
                                'nama' => 'Melahirkan sesuatu yang asli serta bisa meningkatkan kualitas hidup diri sendiri serta orang banyak.'
                            ],
                            [
                                'nama' => 'Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/atau tindakan, serta mengevaluasinya dan mempertimbangkan dampak dan risikonya bagi diri dan lingkungannya dengan menggunakan berbagai perspektif.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan',
                        'elemen' => [
                            [
                                'nama' => 'Bereksperimen dengan berbagai pilihan secara kreatif untuk memodifikasi gagasan sesuai dengan perubahan situasi.'
                            ],
                            [
                                'nama' => 'Mampu mencari alternatif-alternatif penyelesaian suatu masalah dengan mempertimbangkan baik atau buruknya solusi tersebut termasuk keluar dalam tekanan'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Menghasilkan gagasan yang orisinal',
                        'elemen' => [
                            [
                                'nama' => 'Melahirkan gagasan berdasarkan pemikiran sendiri atau tim dengan mempertimbangkan berbagai macam informasi yang sesuai dengan gagasan tersebut.'
                            ],
                            [
                                'nama' => 'Menghasilkan gagasan yang beragam untuk mengekspresikan pikiran dan/atau perasaannya, menilai gagasannya, serta memikirkan segala risikonya dengan mempertimbangkan banyak perspektif seperti etika dan nilai kemanusiaan ketika gagasannya direalisasikan.'
                            ]
                        ],
                    ]
                ],
            ],
            [
                'nama' => 'Bergotong royong',
                'elemen' => [
                    [
                        'nama' => 'Berbagi',
                        'elemen' => [
                            [
                                'nama' => 'Berbagi setiap sumber daya yang dimiliki, termasuk ilmu dan pengetahuan  dengan tetap berpegang teguh pada nilai-nilai kebenaran dan kemajuan bersama.'
                            ],
                            [
                                'nama' => 'Mengupayakan memberi hal yang dianggap penting dan berharga kepada orang-orang yang membutuhkan di masyarakat yang lebih luas (negara, dunia).'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Kepedulian',
                        'elemen' => [
                            [
                                'nama' => 'Melakukan tindakan yang tepat agar orang lain merespon sesuai dengan yang diharapkan dalam rangka penyelesaian pekerjaan dan pencapaian tujuan.'
                            ],
                            [
                                'nama' => 'Mengekspresikan kepedulian pada sesama dan makhluk hidup lainnya.'
                            ],
                            [
                                'nama' => 'Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan berkontribusi sesuai dengan kebutuhan masyarakat untuk menghasilkan keadaan yang lebih baik.'
                            ]
                        ],
                    ],
                    [
                        'nama' => 'Kolaborasi',
                        'elemen' => [
                            [
                                'nama' => 'Aktif menyimak untuk memahami dan menganalisis informasi, gagasan, emosi, keterampilan dan keprihatinan yang disampaikan oleh orang lain dan kelompok menggunakan berbagai simbol dan media secara efektif, serta menggunakan berbagai strategi komunikasi untuk menyelesaikan masalah guna mencapai berbagai tujuan bersama.'
                            ],
                            [
                                'nama' => 'Membangun tim dan mengelola kerjasama untuk mencapai tujuan bersama sesuai dengan target yang sudah ditentukan.'
                            ],
                            [
                                'nama' => 'Menjalin kerjasama dan bersinergi untuk mencapai tujuan dan kebaikan bersama dengan mengesampingkan kepentingan pribadi.'
                            ],
                            [
                                'nama' => 'Menyelaraskan dan menjaga tindakan diri dan anggota kelompok agar sesuai antara satu dengan lainnya serta menerima konsekuensi tindakannya dalam rangka mencapai tujuan bersama.'
                            ],
                            [
                                'nama' => 'Menyelaraskan kapasitas kelompok agar para anggota kelompok dapat saling membantu satu sama lain memenuhi kebutuhan mereka baik secara individual maupun kolektif.'
                            ]
                        ],
                    ]
                ],
            ],
        ];
        foreach($data as $d){
            $dimensi = Dimensi_projek::updateOrCreate([
                'nama' => $d['nama'],
            ]);
            foreach($d['elemen'] as $elemen){
                $new = Elemen_projek::updateOrCreate([
                    'nama' => $elemen['nama'],
                    'dimensi_id' => $dimensi->id,
                ]);
                foreach($elemen['elemen'] as $sub){
                    Elemen_projek::updateOrCreate([
                        'elemen_id' => $new->id,
                        'nama' => $sub['nama'],
                        'dimensi_id' => $dimensi->id,
                    ]);
                }
            }
        }
        return Command::SUCCESS;
    }
}
