    function build_panel_button(link,text,hover) {
         var outer_div = document.createElement("div");
         outer_div.className = "panel panel-default panel-button button-panel-wrapper";
        
         var inner_div = document.createElement("div");
         inner_div.className = "panel-body button-panel";

         if (hover == "true") {
           inner_div.className = inner_div.className + " button-panel-hover";
         }
 
         var a = document.createElement("a");
         a.href = link;

         $(inner_div).append(text);
         $(a).append(inner_div);
         $(outer_div).append(a);
         outer_div.style.outline = "none";
         return outer_div;
    }

    function render_custom() {
         $(".button-panel").each(function() {
             // main table
             var table = document.createElement("table");
             table.class = "button-panel-table";
             table.style.width = "100%";
         
             // content td
             var main_td = document.createElement("td");
             $(main_td).append($(this).html());
             main_td.style.width = "95%";
             
             // glyphicon td
             var glyph_td = document.createElement("td");
             $(glyph_td).append('<span class="glyphicon glyphicon-chevron-right"></span>');
             glyph_td.style.width = "5%";
             glyph_td.style.textAlign = "right";

             // put it together
             var tr = document.createElement("tr");
             $(tr).append(main_td).append(glyph_td);
             $(table).append(tr);
             $(this).empty();
             $(this).append(table);             
         });
    }
