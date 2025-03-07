$(document).ready(function() {
    $("#btn_reload").click(function() {
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var selectline = $('#selectline').val();
        tampil_chart(tgl_awal, tgl_akhir, selectline, chart);
        tampil_chart1(tgl_awal, tgl_akhir, selectline, chart1);
        get_details(tgl_awal, tgl_akhir, selectline);
        get_fcr(tgl_awal, tgl_akhir, selectline);

    });

    var ctx = document.getElementById('chartpie').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {},
        options: {}
    });

    var ctx1 = document.getElementById('chartpie1').getContext('2d');
    var chart1 = new Chart(ctx1, {
        type: 'bar',
        data1: {},
        options: {}
    });
});


function tampil_chart(tgl_awal, tgl_akhir, selectline, chart) {

    $.ajax({
        url: APP_BACKEND + "produksi/grafik_opr_finish_qty",
        method: "GET",
        data: {
            "tgl_awal": tgl_awal,
            "tgl_akhir": tgl_akhir,
            "selectline": selectline
        },
        dataType: "json",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        success: function(data) {
            var operator = [];
            var value = [];
            var coloR = [];
            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };


            for (var i in data.hasil_atari) {
                operator.push(data.hasil_atari[i].operator);
                value.push(data.hasil_atari[i].finish_qty);
                coloR.push(dynamicColors());
            }

            chart.data = {
                    labels: operator,
                    datasets: [{
                        label: 'Finish Qty',
                        backgroundColor: '#00D16A',
                        borderColor: 'rgb(51, 153, 255)',
                        data: value,
                        fill: true,
                    }]
                },


                chart.options = {
                    onClick: function(c, i) {},

                    tooltips: {
                        callbacks: {
                            title: function(tooltipItem, data) {
                                return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function(tooltipItem, data) {
                                return 'Finish Qty : ' + tooltipItem.yLabel.toLocaleString(
                                    "en-US") + ' Pcs';
                            }

                        },
                        label: 'Finish Qty',
                        backgroundColor: '#FFCCFF',
                        titleFontSize: 16,
                        titleFontColor: '#0066ff',
                        bodyFontColor: '#000',
                        bodyFontSize: 14,
                        displayColors: true
                    }

                };
            chart.update();

        }

    });
}

function tampil_chart1(tgl_awal, tgl_akhir, selectline, chart1) {
    $.ajax({
        url: APP_BACKEND + "produksi/grafik_opr_finish_qty",
        method: "GET",
        data: {
            "tgl_awal": tgl_awal,
            "tgl_akhir": tgl_akhir,
            "selectline": selectline
        },
        dataType: "json",
        beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + key);
        },
        success: function(data) {
            var label = [];
            var v_finish_qty = [];
            var v_jam_total = [];
            var v_pcsjam = [];
            var v_cyclejam = [];
            var coloR = [];
            var jam_total = [];
            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };

            for (var i in data.hasil_atari) {
                label.push(data.hasil_atari[i].operator);
                v_finish_qty.push(data.hasil_atari[i].finish_qty);
                v_jam_total.push(data.hasil_atari[i].jam_total);

                if (selectline == '70' || selectline == '50') {
                    v_cyclejam.push(data.hasil_atari[i].cyclejam);
                } else {
                    v_pcsjam.push(data.hasil_atari[i].pcsjam);
                }
                coloR.push(dynamicColors());

                qty_fg = data.hasil_atari[i].finish_qty;
                jam_total = data.hasil_atari[i].jam_total;

            }

            if (selectline == '70' || selectline == '50') {
                chart1.data = {
                    labels: label,
                    datasets: [{
                            yAxisID: 'B',
                            label: 'Jam Kerja',
                            fill: true,
                            borderColor: 'rgb(255, 153, 51)',
                            pointBorderWidth: 10,
                            lineTension: 0.2,
                            data: v_jam_total,
                            type: 'line',
                        },
                        {
                            yAxisID: 'A',
                            label: 'Cycle/Jam',
                            is3D: true,
                            //backgroundColor: coloR,
                            backgroundColor: 'rgb(51, 153, 255)',
                            borderColor: 'rgb(51, 153, 255)',
                            data: v_cyclejam,
                        }


                    ]
                };

            } else {
                chart1.data = {
                    labels: label,
                    datasets: [{
                            yAxisID: 'B',
                            label: 'Jam Kerja',
                            fill: true,
                            borderColor: 'rgb(255, 153, 51)',
                            pointBorderWidth: 10,
                            lineTension: 0.2,
                            data: v_jam_total,
                            type: 'line',
                        },
                        {
                            yAxisID: 'A',
                            label: 'Pcs/Jam',
                            is3D: true,
                            //backgroundColor: coloR,
                            backgroundColor: 'rgb(51, 153, 255)',
                            borderColor: 'rgb(51, 153, 255)',
                            data: v_pcsjam,
                        }


                    ]
                };
            }

            chart1.options = {
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return data['labels'][tooltipItem[0]['index']];
                        },
                        label: function(tooltipItem, data) {
                            return tooltipItem.yLabel.toLocaleString("en-US");
                        }

                    },
                    backgroundColor: '#FFF',
                    titleFontSize: 16,
                    titleFontColor: '#0066ff',
                    bodyFontColor: '#000',
                    bodyFontSize: 14,
                    displayColors: true
                },

                scales: {
                    A: {
                        type: 'linear', 
                        position: 'left',
                        ticks: {
                            beginAtZero: true,
                        },
                        title: {
                            display: true,
                            text: 'Cycle/Jam',
                        },
                    },
                    B: { 
                        type: 'linear',
                        position: 'right',
                        ticks: {
                            beginAtZero: true,
                        },
                        grid: {
                            drawOnChartArea: false, // Nonaktifkan grid untuk sumbu ini
                        },
                        title: {
                            display: true,
                            text: 'Jam Kerja',
                        },
                    },
                },

                onClick: function(c, i) {

                }
            };
            chart1.update();
        }

    });
}

function get_details(tgl_awal, tgl_akhir, selectline) {
    var tb_hasil_produksi = $("#tb_hasil_produksi").DataTable({

        destroy: true,
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,
        ajax: {
            url: APP_BACKEND + 'produksi/detail_hasil_jam_opr',
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: {
                "tgl_awal": tgl_awal,
                "tgl_akhir": tgl_akhir,
                "selectline": selectline
            },

        },
        /*columnDefs: [{

            targets: [0],
            visible: true,
            searchable: false
        },

        {
            targets: [4],
            data: null,

            render: function (data, type, row, meta) {
                if (selectline == '70') {
                    var tothasil = (parseFloat(data.cycle));
                    return tothasil.toLocaleString("en-US");
                } else {
                    
                    return 'test';
                }
            }
        },
        ],*/

        columns: [{
                data: 'operator',
                name: 'operator'
            },
            {
                data: 'finish_qty',
                name: 'finish_qty',
                render: $.fn.dataTable.render.number(',', '.', 0, '')
            },
            {
                data: 'jam_total',
                name: 'jam_total',
                render: $.fn.dataTable.render.number(',', '.', 2, '')
            },
            {
                data: 'pcsjam',
                name: 'pcsjam',
                render: $.fn.dataTable.render.number(',', '.', 2, '')
            },
            {
                data: 'total_cycle',
                name: 'total_cycle',
                render: $.fn.dataTable.render.number(',', '.', 0, '')
            },
            {
                data: 'cyclejam',
                name: 'cyclejam',
                render: $.fn.dataTable.render.number(',', '.', 2, '')
            },
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();

            // Fungsi untuk menghilangkan format dan mengonversi string ke angka
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total kolom 1 (Total Finish Qty)
            var Totalfinishqty = api
                .column(1, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total kolom 2 (Total Jam)
            var TotalJam = api
                .column(2, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total kolom 4 (Total Cycle)
            var Totalcycle = api
                .column(4, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Kolom 3: Total Finish Qty dibagi dengan Total Jam
            var Totalpcsjam = (TotalJam !== 0) ? (Totalfinishqty / TotalJam) : 0;

            // Kolom 5: Total Cycle dibagi dengan Total Jam
            var Totalcyclejam = (TotalJam !== 0) ? (Totalcycle / TotalJam) : 0;

            // Update footer kolom 1
            $(api.column(1).footer()).html(
                Totalfinishqty.toLocaleString("en-US")
            );

            // Update footer kolom 2
            $(api.column(2).footer()).html(
                TotalJam.toLocaleString("en-US")
            );

            // Update footer kolom 3
            $(api.column(3).footer()).html(
                Totalpcsjam.toFixed(2).toLocaleString("en-US")
            );

            // Update footer kolom 4
            $(api.column(4).footer()).html(
                Totalcycle.toLocaleString("en-US")
            );

            // Update footer kolom 5
            $(api.column(5).footer()).html(
                Totalcyclejam.toFixed(2).toLocaleString("en-US")
            );
        }


    });
}

function get_fcr(tgl_awal, tgl_akhir, selectline) {
    var tb_hasil_fcr = $("#tb_hasil_fcr").DataTable({

        destroy: true,
        processing: true,
        serverSide: true,
        searching: false,
        ordering: false,
        ajax: {
            url: APP_BACKEND + 'produksi/detail_hasil_fcr_opr',
            type: "GET",
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + key);
            },
            data: {
                "tgl_awal": tgl_awal,
                "tgl_akhir": tgl_akhir,
                "selectline": selectline
            },

        },
        columnDefs: [{

                targets: [0],
                visible: true,
                searchable: false
            },

            {
                targets: [3],
                data: null,
                
                render: function(data, type, row, meta) {
                    var tothasil = (parseFloat(data.F) + parseFloat(data.CR));
                    return tothasil.toLocaleString("en-US");
                }
            },
        ],
        columns: [{
                data: 'type',
                name: 'type'
            },
            {
                data: 'F',
                name: 'F',
                render: $.fn.dataTable.render.number(',', '.', 0, '')
            },
            {
                data: 'CR',
                name: 'CR',
                render: $.fn.dataTable.render.number(',', '.', 2, '')
            },
            //{ data: 'pcsj', name: 'pcsj', render: $.fn.dataTable.render.number(',', '.', 2, '') },
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total over all pages
            total = api
                .column(1)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            Totalf = api
                .column(1, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(1).footer()).html(
                Totalf.toLocaleString("en-US")
            );

            Totalcr = api
                .column(2, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            $(api.column(2).footer()).html(
                Totalcr.toLocaleString("en-US")
            );

            Totalfcr = api
                .column(3, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return Totalf + Totalcr;
                }, 0);
            $(api.column(3).footer()).html(
                Totalfcr.toLocaleString("en-US")
            );

        }

    });
}