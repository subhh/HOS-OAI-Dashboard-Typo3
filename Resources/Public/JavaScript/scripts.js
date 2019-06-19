/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


function scrollToTop() {
    var repositoryDetails = document.getElementById("repository-details");
    var repositoryId = repositoryDetails.getAttribute('data-repositoryId');
    var repositoryItem = document.getElementById("repository-id-"+repositoryId);
    if (repositoryItem) {
        repositoryItem.classList.add("active");
        repositoryItem.scrollIntoView({behavior: 'auto', block: 'center'});
    }
}

const loadSpinner = '<div id="floatingCirclesG"> \
    <div class="f_circleG" id="frotateG_01"></div> \
    <div class="f_circleG" id="frotateG_02"></div> \
    <div class="f_circleG" id="frotateG_03"></div> \
    <div class="f_circleG" id="frotateG_04"></div> \
    <div class="f_circleG" id="frotateG_05"></div> \
    <div class="f_circleG" id="frotateG_06"></div> \
    <div class="f_circleG" id="frotateG_07"></div> \
    <div class="f_circleG" id="frotateG_08"></div> \
    </div>';


if (d3_oa_dashboard.select('.details-all').empty()) {
    /* only needed for the details view of a single repository */

    var chartRecordCount = {
        chart: c3_oa_dashboard.generate({
            bindto: '#chartRecordCount',
            data: {
                x: 'x',
                xFormat: '%Y-%m',
                columns: [
                    ['x'],
                    ['record_count'],
                ],
                axes: {
                    record_count: 'y',
                },
                types: {
                    record_count: 'line',
                },
                names: {
                    record_count: SUBHH_OA_DASHBOARD_CHART_RECORD_COUNT_LEGEND
                },
                colors: {
                    record_count: '#3F6672',
                },
            },
            grid: {
                x: {
                    show: true
                },
                y: {
                    show: true
                }
            },
            axis: {
                y: {
                    label: {
                        text: SUBHH_OA_DASHBOARD_CHART_YAXIS_LABEL,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (x) {
                            return x % 1 === 0 ? x : '';
                        }
                    },
                },
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m.%Y',
                    }
                }
            },
            padding: {
                right: 50
            }
        }),

        loadData: function () {

            setLoadingSpinner(d3_oa_dashboard.select('#chartRecordCountTimeRanges'));

            d3_oa_dashboard.json(d3_oa_dashboard.select('#chartRecordCountTimeRanges .current .timeRange-uri').attr('data-getRecordCountTimeRangeUri'),
                {
                    method: 'GET',
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                })
                .then(json => {
                    var xGrids = [];
                    json.xGrids.forEach(function (item, index, array) {
                        xGrids.push({'value': item});
                    });
                    this.chart.load({
                        columns: json.data,

                    });
                    unsetLoadingSpinner(d3_oa_dashboard.select('#chartRecordCountTimeRanges'));
                })
                .catch(error => {
                });
        }
    }


    var chartRecordCountOA = {
        chart: c3_oa_dashboard.generate({
            bindto: '#chartRecordCountOA',
            data: {
                x: 'x',
                xFormat: '%Y-%m',
                columns: [
                    ['x'],
                    ['record_count_oa'],
                ],
                axes: {
                    record_count_oa: 'y',
                },
                types: {
                    record_count_oa: 'line',
                },
                names: {
                    record_count_oa: SUBHH_OA_DASHBOARD_CHART_RECORD_COUNT_OA_LEGEND
                },
                colors: {
                    record_count_oa: '#3F6672',
                },
            },
            grid: {
                x: {
                    show: true
                },
                y: {
                    show: true
                }
            },
            axis: {
                y: {
                    label: {
                        text: SUBHH_OA_DASHBOARD_CHART_YAXIS_LABEL,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (x) {
                            return x % 1 === 0 ? x : '';
                        }
                    },
                },
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m.%Y',
                    }
                }
            },
            padding: {
                right: 50
            }
        }),

        loadData: function () {

            setLoadingSpinner(d3_oa_dashboard.select('#chartRecordCountOATimeRanges'));

            d3_oa_dashboard.json(d3_oa_dashboard.select('#chartRecordCountOATimeRanges .current .timeRange-uri').attr('data-getRecordCountTimeRangeUri'),
                {
                    method: 'GET',
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                })
                .then(json => {
                    var xGrids = [];
                    json.xGrids.forEach(function (item, index, array) {
                        xGrids.push({'value': item});
                    });
                    this.chart.load({
                        columns: json.data,
                    });

                    unsetLoadingSpinner(d3_oa_dashboard.select('#chartRecordCountOATimeRanges'));
                })
                .catch(error => {
                });
        }
    }


    /*
     *  Time range pagination
     */
    d3_oa_dashboard.selectAll(".timeRange-prev")
        .on("click", function () {
            var numberOfTimeRanges = d3_oa_dashboard.select(this.parentNode).selectAll('.timeRange-uri').size();
            var current = d3_oa_dashboard.select(this.parentNode).select('.current');
            var currentIndex = parseInt(current.attr('data-index'));
            current.classed('current', false);
            var newCurrentIndex = (currentIndex - 1 < 1) ? 1 : currentIndex - 1;
            var newCurrent = d3_oa_dashboard.select(this.parentNode).select('[data-index="' + newCurrentIndex + '"]');
            newCurrent.classed('current', true);

            if (newCurrent.classed('inactive')) {
                for (var i = 1; i < numberOfTimeRanges + 1; i++) {
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + i + '"]').classed('active', false);
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + i + '"]').classed('inactive', true);
                }
                for (var j = newCurrentIndex; j < newCurrentIndex + 3; j++) {
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + j + '"]').classed('active', true);
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + j + '"]').classed('inactive', false);
                }

            }

            switch (d3_oa_dashboard.select(this.parentNode.parentNode).attr('id')) {
                case "chartRecordCountTimeRanges":
                    chartRecordCount.loadData();
                    break;
                case "chartRecordCountOATimeRanges":
                    chartRecordCountOA.loadData();
                    break;
                case "chartRecordCountFulltextTimeRanges":
                    chartRecordCountFulltext.loadData();
                    break;
            }

            d3_oa_dashboard.event.preventDefault();
        });

    d3_oa_dashboard.selectAll(".timeRange-next")
        .on("click", function () {
            var numberOfTimeRanges = d3_oa_dashboard.select(this.parentNode).selectAll('.timeRange-uri').size();
            var current = d3_oa_dashboard.select(this.parentNode).select('.current');
            var currentIndex = parseInt(current.attr('data-index'));
            current.classed('current', false);
            var newCurrentIndex = (currentIndex + 1 > numberOfTimeRanges) ? numberOfTimeRanges : currentIndex + 1;

            var newCurrent = d3_oa_dashboard.select(this.parentNode).select('[data-index="' + newCurrentIndex + '"]');
            newCurrent.classed('current', true);

            if (newCurrent.classed('inactive')) {
                for (var i = 1; i < numberOfTimeRanges + 1; i++) {
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + i + '"]').classed('active', false);
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + i + '"]').classed('inactive', true);
                }
                for (var j = newCurrentIndex; j > newCurrentIndex - 3; j--) {
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + j + '"]').classed('active', true);
                    d3_oa_dashboard.select(this.parentNode).select('[data-index="' + j + '"]').classed('inactive', false);
                }
            }


            switch (d3_oa_dashboard.select(this.parentNode.parentNode).attr('id')) {
                case "chartRecordCountTimeRanges":
                    chartRecordCount.loadData();
                    break;
                case "chartRecordCountOATimeRanges":
                    chartRecordCountOA.loadData();
                    break;
            }

            d3_oa_dashboard.event.preventDefault();
        });


    d3_oa_dashboard.selectAll(".timeRange-uri")
        .on("click", function () {
            var numberOfTimeRanges = d3_oa_dashboard.select(this.parentNode).selectAll('.timeRange-uri').size();
            var current = d3_oa_dashboard.select(this.parentNode.parentNode).select('.current');
            current.classed('current', false);
            var currentIndex = parseInt(current.attr('data-index'));
            var newCurrent = d3_oa_dashboard.select(this.parentNode);
            newCurrent.classed('current', true);
            d3_oa_dashboard.select(this).attr('disabled', 'true');

            switch (d3_oa_dashboard.select(this.parentNode.parentNode.parentNode).attr('id')) {
                case "chartRecordCountTimeRanges":
                    chartRecordCount.loadData();
                    break;
                case "chartRecordCountOATimeRanges":
                    chartRecordCountOA.loadData();
                    break;
            }

            d3_oa_dashboard.event.preventDefault();
        });


    function setLoadingSpinner(chartNode) {
        chartNode.selectAll(".timeRanges button").attr('disabled', 'true');
        chartNode.select(".timeRanges .current button").html(loadSpinner + chartNode.select(".timeRanges .current button").html());
    }

    function unsetLoadingSpinner(chartNode) {
        chartNode.selectAll('.timeRanges button').attr('disabled', null);
        chartNode.selectAll('.timeRanges button div').remove();
    }


    var chartRepositoryAvailability = {
        chart: c3_oa_dashboard.generate({
            bindto: '#chartRepositoryAvailability',
            size: {
                height: 240,
            },
            data: {
                x: 'x',
                xFormat: '%Y-%m-%d',
                columns: [
                    ['x'],
                    ['availability'],
                ],
                axes: {
                    availability: 'y',
                },
                types: {
                    //availability: 'area-step',
                    availability: 'bar',
                },
                names: {
                    availability: SUBHH_OA_DASHBOARD_CHART_REPOSITORY_AVAILABILITY_LEGEND
                },
                colors: {
                    availability: ' #008000',
                },
            },
            grid: {
                x: {
                    show: true
                },
                y: {
                    show: true
                }
            },
            axis: {
                y: {
                    label: {
                        text: SUBHH_OA_DASHBOARD_CHART_REPOSITORY_AVAILABILITY_YAXIS_LABEL,
                        position: 'outer-middle'
                    },
                    tick: {
                        format: function (x) {
                            return x % 1 === 0 ? x : '';
                        }
                    },
                },
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%d.%m.%Y',
                        rotate: 75,
                        multiline: false
                    },
                }
            },
            padding: {
                right: 50
            }
        }),

        loadData: function () {

            var selection = d3_oa_dashboard.select('#chartRepositoryAvailability');

            if (!selection.empty()) {
                d3_oa_dashboard.json(selection.attr('data-getRepositoryAvailabilityUri'),
                    {
                        method: 'GET',
                        headers: {
                            "Content-type": "application/json; charset=UTF-8"
                        }
                    })
                    .then(json => {
                        var xGrids = [];
                        json.xGrids.forEach(function (item, index, array) {
                            xGrids.push({'value': item});
                        });
                        this.chart.load({
                            columns: json.data,
                        });
                    })
                    .catch(error => {
                    });
            }
        }
    }
}

var chartSets = {
    chart: c3_oa_dashboard.generate({
        bindto: '#chartSets',
        data: {
            x: 'x',
            columns: [
                ['x'],
                ['sets'],
            ],
            axes: {
                sets: 'y',
            },
            types: {
                sets: 'bar',
            },
            names: {
                sets: SUBHH_OA_DASHBOARD_CHART_SETS_LEGEND
            },
            colors: {
                sets: '#3F6672',
            },
            labels: true
        },
        grid: {
            x: {
                show: true
            },
            y: {
                show: true
            }
        },
        axis: {
            rotated: true,
            y: {
                label: {
                    text: SUBHH_OA_DASHBOARD_CHART_YAXIS_LABEL,
                    position: 'inner-bottom'
                },
                tick: {
                    rotate: 75,
                    format: function (x) {
                        return x % 1 === 0 ? x : '';
                    },
                },
            },
            x: {
                type: 'category',
            },
        },
        padding: {
            right: 50,
        }
    }),

    loadData: function () {
        d3_oa_dashboard.json(d3_oa_dashboard.select('#chartSets').attr('data-getRepositorySetsUri'),
            {
                method: 'GET',
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
            .then(json => {
                console.log(json[0].length);

                var newHeight = (json[0].length) * 40;
                this.chart.resize({height: newHeight});
                this.chart.load({
                    columns: json,
                });
            })
            .catch(error => {
            });

        height: 700;
    }
}



var chartLicence = {
    chart: c3_oa_dashboard.generate({
        bindto: '#chartLicence',
        data: {
            columns: [],
            names: {},
            type: 'donut',
        },
        donut: {
            title: SUBHH_OA_DASHBOARD_CHART_LICENCE_TITLE
        },
        legend: {
            position: 'bottom',
            show: false,
            item: {
                onclick: function (id) {
                    this.chart.focus(id);
                    this.chart.tooltip.show();
                }
            }
        },
    }),

    loadData: function() {
        return d3_oa_dashboard.json(d3_oa_dashboard.select('#chartLicence').attr('data-getRecordLicenceUri'),
            {
                method: 'GET',
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            })
            .then(json => {
                this.chart.load({
                    columns: json.data
                });
                this.chart.load({
                    names: json.names
                });

                var width = d3_oa_dashboard.select("#chartLicence").node().getBoundingClientRect().width;
                if (width < 320) {
                    this.height = 400 + Math.round(Object.keys(json.names).length * 11);
                } else {
                   if (width < 650) {
                       this.height = 400 + Math.round(Object.keys(json.names).length * 11);
                   } else {
                       this.height = 400 + Math.round(Object.keys(json.names).length * 4);
                   }
                }
                this.chart.resize({height: this.height});
                this.chart.legend.show();
    });
    },

    height: 350
}


var chartDocumentTypes = {
    chart: c3_oa_dashboard.generate({
        bindto: '#chartDocumentTypes',
        size: {
        },
        data: {
            columns: [],
            names: {},
            type: 'donut',
        },
        donut: {
            title: SUBHH_OA_DASHBOARD_CHART_DOCUMENT_TYPES_TITLE
        },
        legend: {
            position: 'bottom',
            show: false,
            item: {
                onclick: function (id) {
                    this.chart.focus(id);
                    this.chart.tooltip.show();
                }
            }
        },
    }),

    loadData: function() {
            return d3_oa_dashboard.json(d3_oa_dashboard.select('#chartDocumentTypes').attr('data-getRecordDocumentTypesUri'),
                {
                    method: 'GET',
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                })
                .then(json => {
                    this.chart.load({
                        columns: json.data
                    });
                    this.chart.load({
                        names: json.names
                    });

                    var width = d3_oa_dashboard.select("#chartDocumentTypes").node().getBoundingClientRect().width;
                    if (width < 320) {
                        this.height = 400 + Math.round(Object.keys(json.names).length * 11);
                    } else {
                        if (width < 650) {
                            this.height = 400 + Math.round(Object.keys(json.names).length * 11);
                        } else {
                            this.height = 400 +Math.round(Object.keys(json.names).length * 4);
                        }
                    }
                    this.chart.resize({height: this.height});
                    this.chart.legend.show();

                });
    },

    height: 350

}


/*
 * Repository-List:
 *
 * Mark the currently selected repository item as active.
 *
 */
var repositoryList = document.getElementById("repository-list");
repositoryList.addEventListener("load", scrollToTop(), false);


/*
 *  Charts, load data
 */
if (d3_oa_dashboard.select('.details-all').empty()) {
    chartRecordCount.loadData();
    chartRecordCountOA.loadData();
    chartRepositoryAvailability.loadData();
}

chartLicence.loadData();
chartDocumentTypes.loadData();
chartSets.loadData();

