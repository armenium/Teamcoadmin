jQuery(document).ready(function($) {
    var productid = $("#productid").val();
    $.ajax({
        url: "https://jerseybuilder.teamcosportswear.com/api/product/" + productid,
        type: 'GET',
        success: function(result) {
            if (result.data != 'not found') {
                var svgData = result.data.svg_info;
                showInfoSVG(svgData, productid);
                renderColors();
                var url = "https://jerseybuilder.teamcosportswear.com/public/jerseys/" + result.data.url_svg + ".svg";
                xhr = new XMLHttpRequest();
                xhr.open("GET", url, false);
                xhr.overrideMimeType("image/svg+xml");
                xhr.send("");
                document.getElementById("dragSVG").appendChild(xhr.responseXML.documentElement);
                renderButtonSave(productid);
                setDataChange(result.data.dataExtra);
            }
        }
    });
    function showInfoSVG(svgData, productid) {
        var qty = 1;
        var html = '<h5>Preview Colors</h5>';
        html += '<h6>Select color to change</h6>';
        html += '<div class="previewColors">';
        html += '<form class="formInfoColors">';
        html += '<input type="hidden" name="productId" value="' + productid + '">';
        $.each(svgData, function(i, val) {
            if (val.hide == null) {
                html += "<div class='list-colors custom-color-picker-block'>";
                html += "<span>Color " + (qty++) + "</span>";
                html += "<div class='custom-color-picker'>";
                html += "<div fortype='" + val.type + "' id='" + val.class + "' class='color colorChange' style='background-color:" + val.colorCode + "' forclass='" + val.class + "' onclick=''>";
                html += "</div>";
                html += "</div>";
                html += "<label>" + val.colorName + "</label>";
                html += "<input type='hidden' name='customColor[" + val.class + "]' id='custom" + val.class + "' value='"+ val.colorCode +"'>";
                html += "</div>";
            }
        });
        html += '</form>';
        html += "</div>";
        $('#colorSVG').append(html);
    }
    function renderColors() {
        $.ajax({
            url: "https://jerseybuilder.teamcosportswear.com/api/colors",
            type: 'GET',
            success: function(result) {
                var html = '<div>';
                $.each(result.data, function(i, val) {
                    html += "<div class='list-colors'>";
                    html += "<div class='custom-true-color-picker'>";
                    html += "<div class='color' customcolorid='" + val.value_code + "' customcolortitle='" + val.name + "' style='background-color:" + val.value_code + "' data-color='" + val.value_code + "' onclick=''>";
                    html += "</div>";
                    html += "</div>";
                    html += "<label>" + val.name + "</label>";
                    html += "</div>";
                });
                html += "</div>";
                $('#colors').append(html);
            }
        });
    }
    function renderButtonSave(id) {
        var html = '<a id="saveSVG" onclick="">Save Image</a>';
        $('#link').append(html);
        var selectedCustomColors = [];
        $(document).on('click', '#saveSVG', function(event) {
            newSVG();
        });
    }
    function newSVG() {
        let newColors = $('.formInfoColors').serialize();
        $.ajax({
            url: "https://jerseybuilder.teamcosportswear.com/api/image",
            type: 'POST',
            data: newColors,
            success: function(result) {
              
              var urlTemp = 'https://jerseybuilder.teamcosportswear.com/public/jerseys/' + result.data + '.svg';
              
              forceDownload(urlTemp,result.data);
            }
        });
    }
    function forceDownload(url, fileName){
     var xhr = new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.responseType = "blob";
        xhr.onload = function(){
            var urlCreator = window.URL || window.webkitURL;
            var imageUrl = urlCreator.createObjectURL(this.response);
            var tag = document.createElement('a');
            tag.href = imageUrl;
            tag.download = fileName;
            document.body.appendChild(tag);
            tag.click();
            document.body.removeChild(tag);
        }
        xhr.send();
    }
    function setDataChange(jsonData) {
        var itemColor = {
            background: jsonData.background,
            colors: jsonData.colors,
            gradients: [],
            selected: '',
            type: '',
            lineargradients: jsonData.linearGradients,
            colorToGradient: jsonData.colorToGradient,
            calculateGradients: function(startColor, endColor) {
                this.gradients = [];
                for (var i = 0; i < 256; i++) {
                    this.gradients[i] = [];
                    this.gradients[i].push(Math.round(startColor.r + (endColor.r - startColor.r) * i / 255));
                    this.gradients[i].push(Math.round(startColor.g + (endColor.g - startColor.g) * i / 255));
                    this.gradients[i].push(Math.round(startColor.b + (endColor.b - startColor.b) * i / 255));
                }
            },
            setGradient: function(className, startColor, endColor) {
                this.calculateGradients(startColor, endColor);
                lengthOfGradient = this.lineargradients[className].length;
                for (var i = 0; i < lengthOfGradient; i++) {
                    var tmpColorArray = this.gradients[this.lineargradients[className][i]['idxRGB']];
                    var gradColor = itemColor.rgbToHex(tmpColorArray[0], tmpColorArray[1], tmpColorArray[2]);
                    this.lineargradients[className][i]['color'] = gradColor;
                    $('[forClass="' + className + '"][num="' + i + '"]')
                        .css('background-color', gradColor);
                }
                $('.' + className).each(function(i) {
                    $(this).children('stop').each(function(j) {
                        $(this).css('stop-color', itemColor.lineargradients[className][j]['color']);
                    });
                });
            },
            setTmpGradient: function(className, startColor, endColor) {
                this.calculateGradients(startColor, endColor);
                $('.' + className).each(function(i) {
                    $(this).children('stop').each(function(j) {
                        var tmpColorArray = itemColor.gradients[itemColor.lineargradients[className][j]['idxRGB']];
                        var gradColor = itemColor.rgbToHex(tmpColorArray[0], tmpColorArray[1], tmpColorArray[2]);
                        $(this).css('stop-color', gradColor);
                        $('[forClass="' + className + '"][num="' + j + '"]')
                            .css('background-color', gradColor);
                    });
                });
            },
            componentToHex: function(c) {
                var hex = c.toString(16);
                return hex.length == 1 ? "0" + hex : hex;
            },
            rgbToHex: function(r, g, b) {
                return "#" + this.componentToHex(r) + this.componentToHex(g) + this.componentToHex(b);
            },
            hexToRgb: function(hex) {
                var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                return result ? {
                    r: parseInt(result[1], 16),
                    g: parseInt(result[2], 16),
                    b: parseInt(result[3], 16)
                } : null;
            },
            convertRGB: function(color) {
                var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
                var red = parseInt(digits[2]);
                var green = parseInt(digits[3]);
                var blue = parseInt(digits[4]);
                return {
                    r: red,
                    g: green,
                    b: blue
                }
            },
            resetGradientsForColorClass: function(colorClassName) {
                if (typeof itemColor.colorToGradient[colorClassName] !== 'undefined') {
                    for (var iCTG in itemColor.colorToGradient[colorClassName]) {
                        var gradientClass = itemColor.colorToGradient[className][iCTG]['gradientClass'];
                        lengthOfGraduent = itemColor.lineargradients[gradientClass].length;
                        itemColor.setTmpGradient(gradientClass, itemColor.hexToRgb(itemColor.lineargradients[gradientClass][0]['color']),
                            itemColor.hexToRgb(itemColor.lineargradients[gradientClass][lengthOfGraduent - 1]['color']));
                    }
                }
            },
            setGradientsForColorClass: function(colorClassName, colorChange) {
                if (typeof itemColor.colorToGradient[colorClassName] !== 'undefined') {
                    var gradChangeColor = colorChange;
                    for (var iCTG in itemColor.colorToGradient[colorClassName]) {
                        var gradientClass = itemColor.colorToGradient[colorClassName][iCTG]['gradientClass'];
                        lengthOfGraduent = itemColor.lineargradients[gradientClass].length;
                        if (itemColor.colorToGradient[colorClassName][iCTG]['point'] > 0) {
                            itemColor.setGradient(gradientClass, itemColor.hexToRgb(itemColor.lineargradients[gradientClass][0]['color']),
                                itemColor.hexToRgb(gradChangeColor));
                        } else {
                            itemColor.setGradient(gradientClass, itemColor.hexToRgb(gradChangeColor),
                                itemColor.hexToRgb(itemColor.lineargradients[gradientClass][lengthOfGraduent - 1]['color']));
                        }
                    }
                }
            }
        }
        $('body').on('click', 'div.colorChange', function(event) {
            $('.custom-color-picker').css({ 'outline': 'none' });
            $(this).parent().css('outline', 'solid 2px #C43050').css('padding', '1px');
            itemColor.selected = $(this).attr('forClass');
            itemColor.type = $(this).attr('forType');
        });
        $('body').on('mouseenter', 'div.custom-true-color-picker > div', function(event) {
            if (itemColor.selected != '' && itemColor.type != '') {
                $('[forClass="' + itemColor.selected + '"][forType="' + itemColor.type + '"]')
                    .css('background-color', $(this).css('background-color'));
                className = itemColor.selected;
                switch (itemColor.type) {
                    case 'colors':
                    case 'background':
                        $('.' + className).css('fill', $(this).css('background-color'));
                        if (typeof itemColor.colorToGradient[className] !== 'undefined') {
                            var gradChangeColor = itemColor.convertRGB($(this).css('background-color'));
                            gradChangeColor = itemColor.rgbToHex(gradChangeColor.r, gradChangeColor.g, gradChangeColor.b);
                            for (var iCTG in itemColor.colorToGradient[className]) {
                                var gradientClass = itemColor.colorToGradient[className][iCTG]['gradientClass'];
                                lengthOfGraduent = itemColor.lineargradients[gradientClass].length;
                                if (itemColor.colorToGradient[className][iCTG]['point'] > 0) {
                                    itemColor.setTmpGradient(gradientClass, itemColor.hexToRgb(itemColor.lineargradients[gradientClass][0]['color']),
                                        itemColor.hexToRgb(gradChangeColor));
                                } else {
                                    itemColor.setTmpGradient(gradientClass, itemColor.hexToRgb(gradChangeColor),
                                        itemColor.hexToRgb(itemColor.lineargradients[gradientClass][lengthOfGraduent - 1]['color']));
                                }
                            }
                        }
                        break;
                }
            }
        });
        $('body').on('mouseleave', 'div.custom-true-color-picker > div', function(event) {
            if (itemColor.selected != '' && itemColor.type != '') {
                className = itemColor.selected;
                switch (itemColor.type) {
                    case 'colors':
                        $('[forClass="' + className + '"][forType="' + itemColor.type + '"]')
                            .css('background-color', itemColor.colors[className]);
                        $('.' + className).css('fill', itemColor.colors[className]);
                        itemColor.resetGradientsForColorClass(className);
                        break;
                    case 'background':
                        $('[forClass="' + className + '"][forType="' + itemColor.type + '"]')
                            .css('background-color', itemColor.background[className]);
                        $('.' + className).css('fill', itemColor.background[className]);
                        itemColor.resetGradientsForColorClass(className);
                        break;
                }
            }
        });
        $('body').on('click', 'div.custom-true-color-picker > div', function(event) {
            if (itemColor.selected != '' && itemColor.type != '') {
                className = itemColor.selected;
                $('[forClass="' + itemColor.selected + '"][forType="' + itemColor.type + '"]')
                    .css('background-color', $(this).css('background-color'));
                var colorInRgb = itemColor.convertRGB($(this).css('background-color'));
                var colorInHex = itemColor.rgbToHex(colorInRgb.r, colorInRgb.g, colorInRgb.b);
                $('input[name="customColor[' + className + ']"]').val($(this).attr('customcolorid'));
                switch (itemColor.type) {
                    case 'colors':
                        $('.' + itemColor.selected).css('fill', $(this).css('background-color'));
                        itemColor.colors[className] = colorInHex;
                        itemColor.setGradientsForColorClass(className, colorInHex);
                        break;
                    case 'background':
                        $('.' + itemColor.selected).css('fill', $(this).css('background-color'));
                        itemColor.background[className] = colorInHex;
                        itemColor.setGradientsForColorClass(className, colorInHex);
                        break;
                }
                $('input[name="customColor[' + className + ']"]').prev('label').html($(this).attr('customColorTitle'));
            } else {
                alert('Please select color to change.');
            }
        });
    }
});

#shopify-section-product-template .product-single__meta {
    padding-left: 0px;
}
#colors,
#colorSVG,
#dragSVG {
    width: 100%;
}
#colorSVG h5,
h6 {
    text-align: center;
    letter-spacing: 0px;
    text-transform: capitalize;
}
.list-colors {
    display: inline-block;
}
#colorSVG .previewColors {
    text-align: center;
}
.previewColors span {
    font-size: 12px;
}
.previewColors label {
    font-size: 10px;
    text-transform: capitalize;
    letter-spacing: 0px;
    margin-bottom: 2px;
}
#dragSVG {
    text-align: center;
}
.color {
    width: 42px;
    height: 20px;
    box-shadow: 1px 1px 3px #6f6f6f;
    margin: 7px 7px 4px 7px;
    
}
#colors label {
    text-align: center;
    font-size: 10px;
    text-transform: capitalize;
    margin-bottom: 0px;
    letter-spacing: 0px;
}
.custom-color-picker {
    padding: 1px;
    outline: none;
    cursor: pointer;
}
#link{
    margin-bottom: 10px;
}
#saveSVG {
    color: #126CB4;  
    cursor: pointer;
    font-size:12px;
}