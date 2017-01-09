<?php
    /*
     * Global reportico customization .. 
     * Set styles :-
     * Styles can be applied to the report body, details page, group headers, trailers, 
     * cell styles and are specified as a series of CSS like parameters.
     * Styles are applied to HTML and PDF formats
     * 
     * Add page headers, footers with styles to apply to every report page
     *
    */
    function reportico_defaults($reportico)
    {
            // Set up styles
            // Use 
            // $styles = array ( "styleproperty" => "value", .... );
            // $reportico->apply_styleset("REPORTSECTION", $styles, "columnname", WHENTOAPPLY );
            // Where REPORTSECTION is one of ALLCELLS ROW CELL PAGE BODY COLUMNHEADERS GROUPHEADER GROUPHEADERLABEL GROUPHEADERVALUE GROUPTRAILER
            // and WHENTOAPPLY can be PDF or HTML of leave unsepcified/false for both

            // Don't apply  apply body styles to pdf docuement when using fpdf engine
            if (  $reportico->pdf_engine != "fpdf" )
            {
                // REPORT BODY STYLES
                $styles = array(
                    //"background-color" => "#cccccc",
                    //"border-width" => "2px 2px 2px 2px",
                    "border-width" => "0px 0px 0px 0px",
                    "border-style" => "solid",
                    "border-color" => "#000000",
                    //"background-color" => "#eeeeee",
                    //"padding" => "0 20 0 20",
                    //"margin" => "0 0 0 5",
                    "font-family" => "freesans",
                    );
                $reportico->apply_styleset("BODY", $styles, false, "PDF");

                // CRITERIA BOX STYLES
                $styles = array(
                    //"background-color" => "#eeeeee",
                    "background-color" => "#ffffff",
                    "border-style" => "solid",
                    "border-width" => "1px 1px 1px 1px",
                    "border-color" => "#888888",
                    //"margin" => "0px 5px 10px 5px",
                    //"padding" => "0px 5px 0px 5px",
                    );
                $reportico->apply_styleset("CRITERIA", $styles, false, false);
            }

            // PAGE DETAIL BOX STYLES
            $styles = array(
                "margin" => "0 5 0 5",
                );
            $reportico->apply_styleset("PAGE", $styles, false, "PDF");

            // DETAIL ROW BOX STYLES
            $styles = array(
                //"background-color" => "#cccccc",
                "background-color" => "#ffffff",
                "margin" => "0 10 0 10",
                "padding" => "0px 5px 0px 5px",
                );
            $reportico->apply_styleset("ROW", $styles, false, "PDF");

            $styles = array(
                //"background-color" => "#dddddd",
                "background-color" => "#ffffff",
                
                );
            $reportico->apply_styleset("ALLCELLS", $styles, false, "PDF", "lineno() % 2 == 0");


            // GROUP HEADER VALUE STYLES
            /*
            $styles = array(
                "background-color" => "#000000",
                "color" => "#ffffff",
                "font-family" => "comic",
                "font-size" => "18px",
                "padding" => "0 10 0 10",
                "requires-before" => "8cm",
                "margin" => "0 10 0 0",
                );
            $reportico->apply_styleset("GROUPHEADERVALUE", $styles, "PDF");
            */

            //GROUP HEADER LABEL STYLES
            /*
            $styles = array(
                "background-color" => "#000000",
                "color" => "#ffffff",
                "font-family" => "comic",
                "font-size" => "18px",
                "padding" => "0 10 0 10",
                "margin" => "0 0 0 0",
                "requires-before" => "8cm",
                );
            $reportico->apply_styleset("GROUPHEADERLABEL", $styles, "PDF");
            */

            // ALL CELL STYLES
            /*
            $styles = array(
                "font-family" => "times",
                "border-width" => "1px 1px 1px 1px",
                "border-style" => "solid",
                "border-color" => "#888888",
                );
            $reportico->apply_styleset("ALLCELLS", $styles, "PDF");
            */

            // Specific named cell styles
            /*
            $styles = array(
                "color" => "#880000",
                "font-weight" => "bold",
                "font-style" => "italic",
                );
            $reportico->apply_styleset("CELL", $styles, "id", "PDF");
            */

            // Column header styles
            $styles = array(
                //"color" => "#ffffff",
                //"background-color" => "#999999",
                "color" => "#000000",
                "background-color" => "#ffffff",
                "font-weight" => "bold",
                );
            $reportico->apply_styleset("COLUMNHEADERS", $styles, false, "PDF");

            // Page Headers for TCPDF driver ( this is the default )
            if ( $reportico->pdf_engine == "tcpdf" )
            {   
                $param=get_reportico_session_param("user_parameters");
                $user=isset($param['User'])?$param['User']." en ":'';
                // Create Report Title Page Header on every page of PDF
                //$reportico->create_page_header("H1", 1, "{REPORT_TITLE}{STYLE border-width: 1 0 1 0; margin: 15px 0px 0px 0px; border-color: #000000; font-size: 18; border-style: solid;padding:5px 0px 5px 0px; height:1cm; background-color: #000000; color: #ffffff; text-align: center}" );
                $reportico->create_page_header("H1", 1, "{REPORT_TITLE}{STYLE font-size: 15; padding:5px 0px 5px 0px; color: #000000; text-align: center}" );
                $reportico->set_page_header_attribute("H1", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H1", "ShowInPDF", "yes" );
                $reportico->set_page_header_attribute("H1", "justify", "center" );

                // Create Image on every page of PDF
                
                $reportico->create_page_header("H2", 1, "{STYLE width: 60; height: 60; margin: 0 0 0 0; background-color: #003333; background-image:images/udNombre.png;}" );
                $reportico->set_page_header_attribute("H2", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H2", "ShowInPDF", "yes" );
                
                // Create Image on every page of PDF
                $reportico->create_page_header("H3", 1, "Fecha: date('Y-m-d H:i:s'){STYLE font-size: 8; text-align: right; font-style: italic;}" );
                $reportico->set_page_header_attribute("H3", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H3", "ShowInPDF", "yes" );
                $reportico->set_page_header_attribute("H3", "justify", "right" );

                // Create Page No on bottom of PDF page
                $reportico->create_page_footer("F1", 2, "Impreso por: ".$user." Sistema de Gestión Financiera - TIKE {STYLE border-width: 1 0 0 0; margin: 40 0 0 0; font-size: 8; text-align: left; font-style: italic; }" );
                $reportico->create_page_footer("F2", 2, "Página: {PAGE}{STYLE border-width: 1 0 0 0; margin: 40 0 0 0; font-style: italic; }" );
                //$reportico->create_page_footer("F3", 2, "Fuente de datos: Sistema Sicapital {STYLE border-width: 1 0 0 0; margin: 40 0 0 0; font-size: 8; text-align: right; font-style: italic; }" );
            }
            else   // FPDF page headers
            {
                // Create Report Title Page Header on every page of PDF
                //$reportico->create_page_header("H1", 2, "{REPORT_TITLE}{STYLE border-width: 1 0 1 0; margin: 15px 0px 0px 0px; border-color: #000000; font-size: 18; border-style: solid;padding:5px 0px 5px 0px; height:1cm; background-color: #000000; color: #ffffff}" );
                $reportico->create_page_header("H1", 1, "{REPORT_TITLE}{STYLE font-size: 15; padding:5px 0px 5px 0px; color: #000000; text-align: center}" );
                $reportico->set_page_header_attribute("H1", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H1", "ShowInPDF", "no" );
                $reportico->set_page_header_attribute("H1", "justify", "center" );

                // Create Image on every page of PDF
                
                $reportico->create_page_header("H2", 1, "{STYLE width: 60; height: 60; margin: 0 0 0 0; background-color: #003333; background-image:images/udNombre.png;}" );
                $reportico->set_page_header_attribute("H2", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H2", "ShowInPDF", "yes" );                
                
                // Create Image on every page of PDF
                $reportico->create_page_header("H3", 1, "Fecha: date('Y-m-d H:i:s'){STYLE font-size: 10; text-align: right; font-style: italic; }" );
                $reportico->set_page_header_attribute("H3", "ShowInHTML", "no" );
                $reportico->set_page_header_attribute("H3", "ShowInPDF", "yes" );
                $reportico->set_page_header_attribute("H3", "justify", "right" );

                // Create Page No on bottom of PDF page
                $reportico->create_page_footer("F1", 1, "Impreso por: Sistema de Gestión Financiera - TIKE {STYLE font-size: 8; text-align: left; font-style: italic; }" );
                $reportico->create_page_footer("F2", 1, "Pag: {PAGE}{STYLE border-width: 1 0 0 0; margin: 40 0 0 0; font-style: italic; }" );
                $reportico->create_page_footer("F3", 1, "Fuente de datos: Sistema Sicapital {STYLE border-width: 1 0 0 0; margin: 40 0 0 0; font-size: 8; text-align: right; font-style: italic; }" );

            }
    }   
?>
