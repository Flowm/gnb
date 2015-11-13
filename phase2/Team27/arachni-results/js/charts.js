function index_to_shortname( index ){
    return ["sql_injection_timing","unencrypted_password_forms","common_directories","password_autocomplete","common_files","x_frame_options","interesting_responses","http_only_cookies","allowed_methods"][index];
}

function index_to_severity( index ){
    return {"sql_injection_timing":"high","unencrypted_password_forms":"medium","common_directories":"medium","password_autocomplete":"low","common_files":"low","x_frame_options":"low","interesting_responses":"informational","http_only_cookies":"informational","allowed_methods":"informational"}[index_to_shortname(index)];
}

function renderCharts() {
    if( window.renderedCharts )
    window.renderedCharts = true;

    c3.generate({
        bindto: '#chart-issues',
        data: {
            columns: [
                ["Trusted",3,2,2,2,0,1,11,1,1],
                ["Untrusted",0,0,0,0,1,0,0,0,0],
                ["Severity",4,3,3,2,2,2,1,1,1]
            ],
            axes: {
                Severity: 'y2'
            },
            type: 'bar',
            groups: [
                ['Trusted', 'Untrusted']
            ],
            types: {
                Severity: 'line'
            },
            onclick: function (d) {
                var location;

                if( d.name.toLowerCase() == 'severity' ) {
                    location = 'summary/issues/trusted/severity/' + index_to_severity(d.x);
                } else {
                    location = 'summary/issues/' + d.name.toLowerCase() + '/severity/' +
                        index_to_severity(d.x) + '/' + index_to_shortname(d.x);
                }

                goToLocation( location );
            }
        },
        regions: [{"class":"severity-high","start":0,"end":0},{"class":"severity-medium","start":1,"end":2},{"class":"severity-low","start":3,"end":5},{"class":"severity-informational","start":6}],
        axis: {
            x: {
                type: 'category',
                categories: ["Blind SQL Injection (timing attack)","Unencrypted password form","Common directory","Password field with auto-complete","Common sensitive file","Missing 'X-Frame-Options' header","Interesting response","HttpOnly cookie","Allowed HTTP methods"],
                tick: {
                    rotate: 15
                }
            },
            y: {
                label: {
                    text: 'Amount of logged issues',
                    position: 'outer-center'
                }
            },
            y2: {
                label: {
                    text: 'Severity',
                    position: 'outer-center'
                },
                show: true,
                type: 'category',
                categories: [1, 2, 3, 4],
                tick: {
                    format: function (d) {
                        return ["Informational","Low","Medium","High"][d - 1]
                    }
                }
            }
        },
        padding: {
            bottom: 40
        },
        color: {
            pattern: [ '#1f77b4', '#d62728', '#ff7f0e' ]
        }
    });

    c3.generate({
        bindto: '#chart-trust',
        data: {
            type: 'pie',
            columns: [["Trusted",23],["Untrusted",1]]
        },
        pie: {
            onclick: function (d) { goToLocation( 'summary/issues/' + d.id.toLowerCase() ) }
        },
        color: {
            pattern: [ '#1f77b4', '#d62728' ]
        }
    });

    c3.generate({
        bindto: '#chart-elements',
        data: {
            type: 'pie',
            columns: [["form",7],["cookie",1],["server",16]]
        }
    });

    c3.generate({
        bindto: '#chart-severities',
        data: {
            type: 'pie',
            columns: [["high",3],["medium",4],["low",4],["informational",13]]
        },
        color: {
            pattern: [ '#d62728', '#ff7f0e', '#ffbb78', '#1f77b4' ]
        },
        pie: {
            onclick: function (d) {
                goToLocation( 'summary/issues/trusted/severity/' + d.id );
            }
        }
    });

}
