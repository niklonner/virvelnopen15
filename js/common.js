var glob_button_id_uxoeult = 0;
var glob_button_a_uxoeult = [];
var glob_button_color_uxoeult = [];
    function build_panel_button(link,text,color,hover,tick) {
         var outer_div = document.createElement("div");
         outer_div.className = "panel panel-default panel-button button-panel-wrapper";
         outer_div.id = "button_id_uxoeult_"+glob_button_id_uxoeult;
         outer_div.style.backgroundColor = color;
        
         var inner_div = document.createElement("div");
         inner_div.className = "panel-body button-panel";
         inner_div.id = "button_inner_id_uxoeult_"+glob_button_id_uxoeult;

         if (hover == true) {
           inner_div.className = inner_div.className + " button-panel-hover";
         }
         if (tick == true) {
           inner_div.className = inner_div.className + " button-panel-tick";
         } else {
           inner_div.className = inner_div.className + " button-panel-click";
         }
         var a = document.createElement("a");
         a.href = link;
         if (tick == true) {
           var active = false;
           $(a).click(function(){
             active = !active;
             if (active == true) {
               outer_div.style.backgroundColor = "rgb(200,200,255)";
               $(outer_div).find(".glyph-td").empty();
               $(outer_div).find(".glyph-td").append('<span class="glyphicon glyphicon-ok"></span>');
             } else {
               outer_div.style.backgroundColor = color;
               $(outer_div).find(".glyph-td").empty();
             }
             return true;
           });
         }
         glob_button_a_uxoeult[inner_div.id] = a;
         glob_button_color_uxoeult[inner_div.id] = color;
         $(inner_div).append(text);
         $(a).append(inner_div);
         $(outer_div).append(a);
         outer_div.style.outline = "none";
         glob_button_id_uxoeult++;
         return outer_div;
    }

    function disable_tick_button(id) {
      var outer_div = document.getElementById(id);
      var a = $(outer_div).find("a");
      if (a.length == 0) { // button is already disabled
        return;
      }
      var acontents = a.html();
      $(a).unbind();
      $(outer_div).empty();
      $(outer_div).append(acontents);
      outer_div.style.backgroundColor = "rgb(170,170,200)";
      $(outer_div).find(".glyph-td").empty();
      $(outer_div).find(".glyph-td").append('<span class="glyphicon glyphicon-minus"></span>');
    }

    function enable_tick_button(id) {
      if ($("#"+id).find("a").length == 1) {
        return;
      }
      inner_div_id = $("#"+id).find('div').attr('id');
      var a = glob_button_a_uxoeult[inner_div_id];
      $("#"+id).empty();
      var outer_div = document.getElementById(id);
      var active = false;
      $(a).click(function(){
        active = !active;
        if (active == true) {
          outer_div.style.backgroundColor = "rgb(200,200,255)";
          $(outer_div).find(".glyph-td").empty();
          $(outer_div).find(".glyph-td").append('<span class="glyphicon glyphicon-ok"></span>');
        } else {
          outer_div.style.backgroundColor = glob_button_color_uxoeult[inner_div_id];
          $(outer_div).find(".glyph-td").empty();
        }
        return true;
      });
      $("#"+id).append(a);
      $("#"+id).find(".glyph-td").empty();
      document.getElementById(id).style.backgroundColor = glob_button_color_uxoeult[inner_div_id];
    }

    function render_custom() {
        $(".button-panel").each(function() {
            // main table
            var table = document.createElement("table");
            table.className = "button-panel-table";
            table.style.width = "100%";
        
            // content td
            var main_td = document.createElement("td");
            $(main_td).append($(this).html());
            main_td.style.width = "95%";
            
            // glyphicon td
            var glyph_td = document.createElement("td");
            glyph_td.className = "glyph-td";
            glyph_td.style.width = "5%";
            glyph_td.style.textAlign = "right";

            // put it together
            var tr = document.createElement("tr");
            $(tr).append(main_td).append(glyph_td);
            $(table).append(tr);
            $(this).empty();
            $(this).append(table);             
        });
        $(".button-panel-click").each(function() {
            $(this).find(".glyph-td").append('<span class="glyphicon glyphicon-chevron-right"></span>');
        });
    }

    function build_expand_button(outer_text, inner_text, color) {
         var outer_div = document.createElement("div");
         var outer_text_div = document.createElement("div");
         outer_text_div.className = "panel panel-default panel-button button-panel-wrapper";
         outer_text_div.id = "button_id_uxoeult_"+glob_button_id_uxoeult;
         var bgcol = typeof color !== 'undefined' ? color : "#FFFFFF" ;
         outer_text_div.style.backgroundColor = bgcol;
         var inner_div = document.createElement("div");
         inner_div.className = "panel-body button-panel";
         inner_div.id = "button_inner_id_uxoeult_"+glob_button_id_uxoeult;

         inner_div.className = inner_div.className + " button-panel-hover";

         var a = document.createElement("a");
         a.href = "javascript:;";
         // main table
         var table = document.createElement("table");
         table.className = "button-panel-table";
         table.style.width = "100%";
        
         // content td
         var main_td = document.createElement("td");
         $(main_td).append(outer_text);
         main_td.style.width = "95%";
            
         // glyphicon td
         var glyph_td = document.createElement("td");
         glyph_td.className = "glyph-td";
         glyph_td.style.width = "5%";
         glyph_td.style.textAlign = "right";
         $(glyph_td).append("<span class=\"glyphicon glyphicon-chevron-down\"></span>");

         // put it together
         var tr = document.createElement("tr");
         $(tr).append(main_td).append(glyph_td);
         $(table).append(tr);

         $(inner_div).append(table);
         $(a).append(inner_div);
         $(outer_text_div).append(a);
         outer_text_div.style.outline = "none";

         var inner_text_div = document.createElement("div");
         inner_text_div.className = "panel panel-default";
         inner_text_div.style.display = "none";
         inner_text_div.style.marginBottom = "0px";

         var inner_inner_div = document.createElement("div");
         inner_inner_div.className = "panel-body button-panel";
         inner_inner_div.style.backgroundColor = '#FAFAFA';
         $(inner_inner_div).append(inner_text);
         $(inner_text_div).append(inner_inner_div);

         var active = false;
         $(a).click(function() {
           active = !active;
           if (active) {
             inner_text_div.style.display = "";
             $(glyph_td).empty();
             $(glyph_td).append("<span class=\"glyphicon glyphicon-chevron-up\"></span>");
             outer_text_div.style.backgroundColor = '#DADADA';
           } else {
             inner_text_div.style.display = "none";
             $(glyph_td).empty();
             $(glyph_td).append("<span class=\"glyphicon glyphicon-chevron-down\"></span>");
             outer_text_div.style.backgroundColor = bgcol;
           }
         });

         $(outer_div).append(outer_text_div);
         $(outer_div).append(inner_text_div);
         glob_button_id_uxoeult++;
         return outer_div;
      
    }
	
