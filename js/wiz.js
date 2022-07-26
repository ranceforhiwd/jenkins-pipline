$(document).ready(function(){
    
    /******Extension June 13, 2022 */
    /*$('body #videoselect').change(function(){        
        console.log('selected:'+$(this).val());
        var x;

        switch ($(this).val()) {
            case '2':
              x='src="https://www.youtube.com/embed/OH2E8P0s-fw"'
              break;
              case '3':
                x='src="https://www.youtube.com/embed/H-fEDunJbdA"'
              break;
              case '4':
                x='src="https://www.youtube.com/embed/F2dnor29COA"'
              break;            
            default:
                x='src="https://www.youtube.com/embed/poLKu-exm0M"'
          }

        select_video(x);
    });*/

    
    /*$('body fieldset#stage3 button').click(function(){        
      console.log('selected:'+$(this).val());
      var x = ['excursions','salon','catering','villaselect'];
      var j = ['firstname','lastname','telephone','email','checkin','checkout'];

      for(var i in j){
        $("#selections").append('<div class="container"><div class="row"><div class="col-6 choices">'+j[i]+'</div><div class="col-6 choices">'+$('input[name="'+j[i]+'"]').val()+'</div></div></div>');
      }
      
      for(var k in x){
        $("#selections").append('<div class="container"><div class="row"><div class="col-6 choices">'+x[k]+'</div><div class="col-6 choices">'+$('form select#'+x[k]+' option:selected').text()+'</div></div></div>');
      }            
    });*/

    $('#submitButton').click(function(){       
      console.log('pressed');
      var w = '<h3>Processing Quote</h3><br><br>';
      var x = '<div id="outer" class="container-fluid d-inline-block"><div id="spinrow"><div style="position:relative;left:50%;"  id="spin" class="spinner-border" role="status"><span class="sr-only"></span></div></div></div>';
      var y = '<p><img src="https://ofc.quickfixtrips.fun/trips/logo-v1.2.1.png" class="img-responsive fit-image" /></p>';
      $('#msform').css('display','none');
      $("#formsection" ).parent().css( "background", "#1C4669" );
      //$("#formsection" ).parent().css( "height", "auto" );  
    });
   
    /*Sfunction select_video(x){
        if(x==null){
            x='src="https://www.youtube.com/embed/poLKu-exm0M"'
        }
        var title=' title="YouTube video player"';
        var allow=' allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>';

        $("div#villavideo").html('<iframe class="embed-responsive-item" '+x+title+' frameborder="0" '+allow+'</iframe>');
    }

    select_video();*/
   
   
  // When DOM is loaded this 
  // function will get executed
  $(() => {
      // function will get executed 
      // on click of submit button
      $("#submitButton_old").click(function(ev) {
          var form = $("#msform");
          var url = form.attr('action');
          $.ajax({
              type: "POST",
              url: url,
              dataType: 'jsonp',
              data: form.serialize(),
              success: function(data) {
                  
                  // Ajax call completed successfully
                  console.log("Form Submited Successfully");
                  console.log(data);                       
              },
              error: function(data) {
                  
                  // Some error in ajax call
                  //alert("some Error");
                  console.log('error');
              }
          });
      });
  });
  
  
});