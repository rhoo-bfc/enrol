"use strict";

var positionsToShow = 20;

window.rotateViews = false;

window.panelHeaders = {
    
    'feed_queue_16_to_18'            : 'Next Up 18 and Under Enrolment',
    'feed_queue_19_plus'             : 'Next Up 19 Plus',
    'feed_queue_missed_appointments' : 'Next Missed Appointments'
    
};

$(document).foundation();

$(document).ready(function() {
    $('#clock').clock( { utc: false, utc_label: true, } );
    $("#feed-ticker").marquee();
});

var modal = {
    
    show : function() {
        $('#busyModal').foundation('open');
    },
    
    hide : function() {       
        $('#busyModal').foundation('close');
    },
    
    message : function( msg ) {
        
       $('#busyModal .busyMessage').empty().append( msg );
    },
    
    error : function( msg ) {
        
       var msg = 'Whoops, an error has ocurred!<br /> The page will refresh shortly.';
       modal.message( msg );
       setTimeout( modal.refresh , 5000);
    },
    
    pollRefresh : function() {
       var msg = 'Please wait, checking enrollee queue';
       modal.message( msg );
       modal.show();
       modal.refresh(); 
    },
    
    refresh : function() {
        window.location.reload(true);
    }
}

$.ajaxSetup({
  async: false
});

$( document ).ajaxError(function() {
  modal.error();
});

$( document ).ajaxSend(function() {
  modal.show();
})

$( document ).ajaxComplete(function() {
  modal.hide();
});

 function doInfoBoardStats() {
     
     $.getJSON( "/stats", function( data ) {
         
         $('#avgWaitTimeIndicator').empty().append( data[0].avg_wait_mins );  
         $('#avgEnrolTimeIndicator').empty().append( data[0].avg_enrolment_mins );  
         
         setTimeout(doInfoBoardStats, 30000);
     });
 }

 function doEnrolStats() {
     
    function flashElement( e ) {
        
        $(e).animate({backgroundColor: "#f7fc74" }).animate({backgroundColor: "#ffffff" });
        
    }
    
    $.getJSON( "/stats", function( data ) {
        
        $('#enrolCount').empty().append( data[0].enrolled_count );    
        if ( data[0].enrolled_count != $('#enrolCountIndicator').attr('data-current') ) {
            flashElement( $('#enrolCount').parent() );
        }     
        $('#enrolCountIndicator').attr('data-current',data[0].enrolled_count);
        
        $('#failedEnrolCount').empty().append( data[0].failed_enrolled_count );
        if ( data[0].failed_enrolled_count != $('#failedEnrolCountIndicator').attr('data-current') ) {
            flashElement( $('#failedEnrolCount').parent() );
        }  
        $('#failedEnrolCountIndicator').attr('data-current',data[0].failed_enrolled_count);
        
        $('#avgWaitTime').empty().append( data[0].avg_wait_mins );
        if ( $('#avgWaitTimeIndicator').attr('data-current') > data[0].avg_wait_mins) {
            $('#avgWaitTimeIndicator').removeClass('fi-arrow-down').removeClass('fi-arrow-up').addClass('fi-arrow-down');
        } else {
            $('#avgWaitTimeIndicator').removeClass('fi-arrow-down').removeClass('fi-arrow-up').addClass('fi-arrow-up');
        }
        if ( data[0].avg_wait_mins != $('#avgWaitTimeIndicator').attr('data-current') ) {
            flashElement( $('#avgWaitTime').parent() );
        } 
        $('#avgWaitTimeIndicator').attr('data-current',data[0].avg_wait_mins);
        
        
        $('#avgEnrolTime').empty().append( data[0].avg_enrolment_mins );
        if ( $('#avgEnrolTimeIndicator').attr('data-current') > data[0].avg_enrolment_mins) {
            $('#avgEnrolTimeIndicator').removeClass('fi-arrow-down').removeClass('fi-arrow-up').addClass('fi-arrow-down');
        } else {
            $('#avgEnrolTimeIndicator').removeClass('fi-arrow-down').removeClass('fi-arrow-up').addClass('fi-arrow-up');
        }
        if ( data[0].avg_enrolment_mins != $('#avgEnrolTimeIndicator').attr('data-current') ) {
            flashElement( $('#avgEnrolTime').parent() );
        } 
        $('#avgEnrolTimeIndicator').attr('data-current',data[0].avg_enrolment_mins);
        
        setTimeout(doEnrolStats, 30000);
    });   
    
    /*
     * 
    $('#avgEnrolTimeIndicator').attr('data-current',100);
    $('#enrolCountIndicator').attr('data-current',100);
    $('#avgWaitTimeIndicator').attr('data-current',100);
    $('#failedEnrolCountIndicator').attr('data-current',100);
     * 
     * 
     */
    
}

function renderInfoTable( view, target, page , offset, rows ) {
    
        if ( typeof page === 'undefined' ) {
            page = 1;
        }
    
        if ( typeof offset === 'undefined' ) {
            offset = 0;
        }
        
        if ( typeof rows === 'undefined' ) {
            rows = positionsToShow;
        }
        
        var route = "/feed/" + view 
                    + "/" + page
                    + "/" + offset
                    + "/" + rows;
        
        modal.message('Refreshing - please wait');
        modal.show();
        $.getJSON( route , function( data ) {

          var headers = data.data[0] ? Object.keys(data.data[0]) : [];

          var table = '<table class="dashboard">';
                  if ( headers ) {
                   table += '<tr>';
                   
                   $(headers).each(function(key, value) {
                     
                     if ( value != '&nbsp;' ) {
                     
                        var cspan = 'colspan=1';
                        if ( value.toUpperCase() === 'please go to service desk'.toUpperCase() ) {
                            var cspan = 'colspan=2';
                        }
                        
                        table += '<th ' + cspan + '>' + value + '</th>';  
                     
                     }
                   });    
                   table += '</tr>';
                  }

                  $(data.data).each(function(key, row) {
                     table += '<tr>';
                     $(headers).each(function(key, value) {
                        table += '<td>' + row[value] + '</td>';  
                     }) 
                     table += '</tr>';
                  });    

                  table += '</table>';

          $(target).empty().append( table );
          
          if ( (typeof window.rotateViews != 'undefined') && 
                 ( window.rotateViews === true ) 
               )  {
               
               if (typeof window.panelHeaders[view] != 'undefined' ) {
                   $('[data-title="' + target + '"]').empty().append(window.panelHeaders[view]);
               }
          };
          
          modal.hide();

        });
        
        setTimeout(function() {
            
            if ( (typeof window.rotateViews != 'undefined') && 
                 ( window.rotateViews === true ) 
               )  {
            
                if ( view === 'feed_queue_16_to_18' ) {
                    view = 'feed_queue_19_plus';
                } else if ( view === 'feed_queue_19_plus' ) {
                    view = 'feed_queue_16_to_18';
                }
            
           }
            
            renderInfoTable(view,target, page , offset, rows);
        }, 5000);
        
}

$(document).ready(function() {
    
    function renderTable( view, page) {
        
        modal.message('Refreshing - please wait');
        modal.show();
        $.getJSON( "/feed/" + view + '?page=' + page , function( data ) {
            
          var headers = data.data[0] ? Object.keys(data.data[0]) : [];
          
          var table = '<table class="dashboard">';
          if ( headers ) {
           table += '<tr>';
           $(headers).each(function(key, value) {
             table += '<th>' + value + '</th>';  
           });    
           table += '</tr>';
          }
          
          $(data.data).each(function(key, row) {
             table += '<tr>';
             $(headers).each(function(key, value) {
                table += '<td>' + row[value] + '</td>';  
             }) 
             table += '</tr>';
          });    
          
          table += '</table>';
          
          var paginator = '<small>' + data.total + ' results</small>'; 
          paginator    += '<ul class="pagination" role="navigation" aria-label="Pagination">'; 
          
          if ( data.prev_page_url ) {
            paginator    += '<li class="pagination-previous"><a data-page="' + (data.current_page-1) + '" href="' + data.prev_page_url + '" aria-label="Next page">Previous <span class="show-for-sr">page</span></a></li>';
          } else {
            paginator    += '<li class="pagination-previous disabled">Previous</li>'; 
          }
          
          for (var page = 1; page <= data.last_page; page++) {
              
              if ( data.current_page == page ) {
                  
                paginator    += '<li class="current"> ' + page + ' </li>';
              } else {
              
                paginator    += '<li><a data-page="' + page + '" href="/feed/' + view + '?page=' + page + '" aria-label="Page 2">' + page + '</a></li>';
              }

          }
          
          if ( data.next_page_url ) {
            paginator    += '<li class="pagination-next"><a data-page="' + (data.current_page+1) + '" href="' + data.next_page_url + '" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>';
          } else {
            paginator    += '<li class="pagination-next disabled">Next</li>'; 
          }
          
          paginator    += '</ul>';
          $('[data-view=' + view + ']').empty().append( table + paginator );
          modal.hide();
          
          $('.pagination [href]').click(function() {
                
                renderTable( view , $(this).attr('data-page') );
                return false;
          });
          
        });
    }
    
    $('#expireServiceDesks').click(function() {
        
        if ($('#expireServiceDesks').hasClass('disabled')) {
            return;
        }
        
        $('#expireServiceDesks').addClass('disabled');
        $.post( '/admin/expire' , function( data ) {
            if ( data.STATUS === 'OK' ) {
                
                var message = data.CLEARED_SESSION + ' service desks have been deactivated.';
                var messageBox = $('#messageTemplate').clone().css({'display':'block'});
                $( messageBox ).find( '[data-message]' ).empty().append( message );
                $('#dash-messages-container').empty().append( messageBox );
                $('#expireServiceDesks').removeClass('disabled');
            }
        });
    });
    
    $('#dashboard-tabs').on('change.zf.tabs', function(event) {

        var view = $('div[data-tabs-content="'+$(this).attr('id')+'"]').find('.tabs-panel.is-active').attr('data-view');

        renderTable( view, 1 );

    });
    
    $(document).ready(function() {
        $('#dashboard-tabs').trigger('change.zf.tabs');
    });
    
    $( '[data-queue="SWITCH"]' ).change(function() {
  
        var queue = $(this).val();
        
        $.ajax({
            type: 'GET',
            dataType: "json",
            url: '/queue/switch/' + queue ,
            beforeSend:function(){
                modal.message( 'Please wait - switching queues' )
                modal.show();
            },
            success:function(res){
                
                if ( res.STATUS === 'OK' ) {
                    modal.hide();
                }
                
                if ( res.ACTION === 'REFRESH' ) {
                    modal.refresh();
                }
                
                if (res.STATUS === 'ERROR') {
                    modal.error();
                }
                
            },
            error:function(res){
                
                modal.error();
            }
        });
  
    });
    
    $('#submitNotes').click(function() {
        
        $('#completeDescription').hide();
        if ( !$.trim( $('[name="notes"]').val() ) ) {  
            
           $('[name="notes"]').val( $.trim( $('[name="notes"]').val() ) );
           $('#completeDescription').show();
           return;
        }
        
        $('#notesModal').foundation('close');
        $('[data-action=FAI]').removeClass('disabled').trigger('click');
        return false;
    });
    
    $('[data-action]').click(function() {
  
        if ( true === $(this).hasClass('disabled') ) {
            return false;
        }

        var action = $(this).attr('data-action');  
        var notes  = $.trim( $('[name="notes"]').val());
        var reason = $('[name="reason"]').val();
        var id     = $('[name=id]').val();
        $(this).addClass('disabled');
        if ( action === 'STA' ) {

            $('[data-action=NOS]').addClass('disabled');
            $('[data-action=NEX]').addClass('disabled');
            $('[data-action=COM]').removeClass('disabled');
            $('[data-action=FAI]').removeClass('disabled');
            reason = null;
        }

        if ( action === 'NOS' ) {

            $('[data-action=STA]').addClass('disabled');
            $('[data-action=NEX]').removeClass('disabled');
            reason = null;
        }

        if ( action === 'COM' ) {

            $('[data-action=STA]').addClass('disabled');
            $('[data-action=FAI]').addClass('disabled');
            $('[data-action=NEX]').removeClass('disabled');
            reason = null;
        }

        if ( action === 'FAI' ) {

            $('[data-action=STA]').addClass('disabled');
            $('[data-action=COM]').addClass('disabled');
            $('[data-action=NEX]').removeClass('disabled');
            if ( notes === '' ) {
                $('#notesModal').foundation('open');
                return;
            }
        }

        if ( action === 'NEX' ) {

            $('[data-action=NOS]').removeClass('disabled');
            $('[data-action=STA]').removeClass('disabled');
            $('[data-action=COM]').addClass('disabled');
            $('[data-action=FAI]').addClass('disabled');
            reason = null;
        }

        $.ajax({
            type: 'POST',
            dataType: "json",
            url: '/enrollee/status/' + id ,
            data: { 'status' : action, 'notes' : notes, 'reason' : reason },
            beforeSend:function(){ 
                modal.show();
            },
            success:function(res){
                
                if ( res.STATUS === 'OK' ) {
                    modal.hide();
                }
                
                if ( res.ACTION === 'REFRESH' ) {
                    modal.refresh();
                }
                
                if (res.STATUS === 'ERROR') {
                    modal.error();
                }
                
            },
            error:function(res){
                
                modal.error();
            }
        });

    });
    
    $('#system-online').change(function() {
  
  
        if ( true === $('#system-online').is(':checked') ) {

          $.getJSON( "/admin/config/system_status?val=1", function(data) {
            console.log( data );
          });


        } else {

          $.getJSON( "/admin/config/system_status?val=0", function(data) {
            console.log( data );
          });
        }

      });
    
})
