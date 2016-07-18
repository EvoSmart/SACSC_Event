<?php
class pdfreport_VIPguests_grid
{
   var $Ini;
   var $Erro;
   var $Pdf;
   var $Db;
   var $rs_grid;
   var $nm_grid_sem_reg;
   var $SC_seq_register;
   var $nm_location;
   var $nm_data;
   var $nm_cod_barra;
   var $sc_proc_grid; 
   var $nmgp_botoes = array();
   var $Campos_Mens_erro;
   var $NM_raiz_img; 
   var $Font_ttf; 
   var $vipguests_id = array();
   var $events_id = array();
   var $organisation = array();
   var $photo = array();
   var $name = array();
   var $contactnumber = array();
   var $email = array();
//--- 
 function monta_grid($linhas = 0)
 {

   clearstatcache();
   $this->inicializa();
   $this->grid();
 }
//--- 
 function inicializa()
 {
   global $nm_saida, 
   $rec, $nmgp_chave, $nmgp_opcao, $nmgp_ordem, $nmgp_chave_det, 
   $nmgp_quant_linhas, $nmgp_quant_colunas, $nmgp_url_saida, $nmgp_parms;
//
   $this->nm_data = new nm_data("en_us");
   include_once("../_lib/lib/php/nm_font_tcpdf.php");
   $this->default_font = '';
   $this->default_font_sr  = '';
   $this->default_style    = '';
   $this->default_style_sr = 'B';
   $Tp_papel = "A5";
   $old_dir = getcwd();
   $File_font_ttf     = "";
   $temp_font_ttf     = "";
   $this->Font_ttf    = false;
   $this->Font_ttf_sr = false;
   if (empty($this->default_font) && isset($arr_font_tcpdf[$this->Ini->str_lang]))
   {
       $this->default_font = $arr_font_tcpdf[$this->Ini->str_lang];
   }
   elseif (empty($this->default_font))
   {
       $this->default_font = "Times";
   }
   if (empty($this->default_font_sr) && isset($arr_font_tcpdf[$this->Ini->str_lang]))
   {
       $this->default_font_sr = $arr_font_tcpdf[$this->Ini->str_lang];
   }
   elseif (empty($this->default_font_sr))
   {
       $this->default_font_sr = "Times";
   }
   $_SESSION['scriptcase']['pdfreport_VIPguests']['default_font'] = $this->default_font;
   chdir($this->Ini->path_third . "/tcpdf/");
   include_once("tcpdf.php");
   chdir($old_dir);
   $this->Pdf = new TCPDF('L', 'mm', $Tp_papel, true, 'UTF-8', false);
   $this->Pdf->setPrintHeader(false);
   $this->Pdf->setPrintFooter(false);
   if (!empty($File_font_ttf))
   {
       $this->Pdf->addTTFfont($File_font_ttf, "", "", 32, $_SESSION['scriptcase']['dir_temp'] . "/");
   }
   $this->Pdf->SetDisplayMode('real');
   $this->aba_iframe = false;
   if (isset($_SESSION['scriptcase']['sc_aba_iframe']))
   {
       foreach ($_SESSION['scriptcase']['sc_aba_iframe'] as $aba => $apls_aba)
       {
           if (in_array("pdfreport_VIPguests", $apls_aba))
           {
               $this->aba_iframe = true;
               break;
           }
       }
   }
   if ($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['iframe_menu'] && (!isset($_SESSION['scriptcase']['menu_mobile']) || empty($_SESSION['scriptcase']['menu_mobile'])))
   {
       $this->aba_iframe = true;
   }
   $this->nmgp_botoes['exit'] = "on";
   $this->sc_proc_grid = false; 
   $this->NM_raiz_img = $this->Ini->root;
   $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
   $this->nm_where_dinamico = "";
   $this->nm_grid_colunas = 0;
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['campos_busca']))
   { 
       $Busca_temp = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['campos_busca'];
       if ($_SESSION['scriptcase']['charset'] != "UTF-8")
       {
           $Busca_temp = NM_conv_charset($Busca_temp, $_SESSION['scriptcase']['charset'], "UTF-8");
       }
       $this->vipguests_id[0] = $Busca_temp['vipguests_id']; 
       $tmp_pos = strpos($this->vipguests_id[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->vipguests_id[0] = substr($this->vipguests_id[0], 0, $tmp_pos);
       }
       $vipguests_id_2 = $Busca_temp['vipguests_id_input_2']; 
       $this->vipguests_id_2 = $Busca_temp['vipguests_id_input_2']; 
       $this->events_id[0] = $Busca_temp['events_id']; 
       $tmp_pos = strpos($this->events_id[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->events_id[0] = substr($this->events_id[0], 0, $tmp_pos);
       }
       $events_id_2 = $Busca_temp['events_id_input_2']; 
       $this->events_id_2 = $Busca_temp['events_id_input_2']; 
       $this->organisation[0] = $Busca_temp['organisation']; 
       $tmp_pos = strpos($this->organisation[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->organisation[0] = substr($this->organisation[0], 0, $tmp_pos);
       }
       $this->photo[0] = $Busca_temp['photo']; 
       $tmp_pos = strpos($this->photo[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->photo[0] = substr($this->photo[0], 0, $tmp_pos);
       }
       $this->email[0] = $Busca_temp['email']; 
       $tmp_pos = strpos($this->email[0], "##@@");
       if ($tmp_pos !== false)
       {
           $this->email[0] = substr($this->email[0], 0, $tmp_pos);
       }
   } 
   $this->nm_field_dinamico = array();
   $this->nm_order_dinamico = array();
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_filtro'];
   $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
   $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
   $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz; 
   $_SESSION['scriptcase']['contr_link_emb'] = $this->nm_location;
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['qt_col_grid'] = 1 ;  
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['cols']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['cols']))
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['qt_col_grid'] = $_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['cols'];  
       unset($_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['cols']);
   }
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_select']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_select'] = array(); 
   } 
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_quebra']))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_grid'] = "" ; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_ant']  = ""; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_desc'] = "" ; 
   }   
   if (!empty($nmgp_parms) && $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['opcao'] != "pdf")   
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['opcao'] = "igual";
       $rec = "ini";
   }
   if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig']) || $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['prim_cons'] || !empty($nmgp_parms))  
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['prim_cons'] = false;  
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'] = " where VIPguests_Id = '" . $_SESSION['VIPguests_Id'] . "'";  
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq']        = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_ant']    = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'];  
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['cond_pesq']         = ""; 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_filtro'] = "";
   }   
   if  (!empty($this->nm_where_dinamico)) 
   {   
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq'] .= $this->nm_where_dinamico;
   }   
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_filtro'];
//
   if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['tot_geral'][1])) 
   { 
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['sc_total'] = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['tot_geral'][1] ;  
   }
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_ant'] = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq'];  
//----- 
   if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mysql))
   { 
       $nmgp_select = "SELECT VIPguests_Id, events_Id, organisation, photo, name, contactNumber, email from " . $this->Ini->nm_tabela; 
   } 
   else 
   { 
       $nmgp_select = "SELECT VIPguests_Id, events_Id, organisation, photo, name, contactNumber, email from " . $this->Ini->nm_tabela; 
   } 
   $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq']; 
   $nmgp_order_by = ""; 
   $campos_order_select = "";
   foreach($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_select'] as $campo => $ordem) 
   {
        if ($campo != $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_grid']) 
        {
           if (!empty($campos_order_select)) 
           {
               $campos_order_select .= ", ";
           }
           $campos_order_select .= $campo . " " . $ordem;
        }
   }
   if (!empty($_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_grid'])) 
   { 
       $nmgp_order_by = " order by " . $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_grid'] . $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['ordem_desc']; 
   } 
   if (!empty($campos_order_select)) 
   { 
       if (!empty($nmgp_order_by)) 
       { 
          $nmgp_order_by .= ", " . $campos_order_select; 
       } 
       else 
       { 
          $nmgp_order_by = " order by $campos_order_select"; 
       } 
   } 
   $nmgp_select .= $nmgp_order_by; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['order_grid'] = $nmgp_order_by;
   $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select; 
   $this->rs_grid = $this->Db->Execute($nmgp_select) ; 
   if ($this->rs_grid === false && !$this->rs_grid->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1) 
   { 
       $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg()); 
       exit ; 
   }  
   if ($this->rs_grid->EOF || ($this->rs_grid === false && $GLOBALS["NM_ERRO_IBASE"] == 1)) 
   { 
       $this->nm_grid_sem_reg = $this->Ini->Nm_lang['lang_errm_empt']; 
   }  
// 
 }  
// 
 function Pdf_init()
 {
     if ($_SESSION['scriptcase']['reg_conf']['css_dir'] == "RTL")
     {
         $this->Pdf->setRTL(true);
     }
     $this->Pdf->setHeaderMargin(0);
     $this->Pdf->setFooterMargin(0);
     if ($this->Font_ttf)
     {
         $this->Pdf->SetFont($this->default_font, $this->default_style, 16, $this->def_TTF);
     }
     else
     {
         $this->Pdf->SetFont($this->default_font, $this->default_style, 16);
     }
     $this->Pdf->SetTextColor(0, 0, 0);
 }
// 
 function Pdf_image()
 {
   $this->Pdf->setVisibility('screen');
   if ($_SESSION['scriptcase']['reg_conf']['css_dir'] == "RTL")
   {
       $this->Pdf->setRTL(false);
   }
   $SV_margin = $this->Pdf->getBreakMargin();
   $SV_auto_page_break = $this->Pdf->getAutoPageBreak();
   $this->Pdf->SetAutoPageBreak(false, 0);
   $this->Pdf->Image($this->NM_raiz_img . $this->Ini->path_img_global . "/grp__NM__bg__NM__6112 SACSC CONGRESS DELEGATE TAG_T1_1.jpg", -2, -1, 211, 148, '', '', '', false, 300, '', false, false, 0);
   $this->Pdf->SetAutoPageBreak($SV_auto_page_break, $SV_margin);
   $this->Pdf->setPageMark();
   if ($_SESSION['scriptcase']['reg_conf']['css_dir'] == "RTL")
   {
       $this->Pdf->setRTL(true);
   }
   $this->Pdf->setVisibility('all');
 }
// 
//----- 
 function grid($linhas = 0)
 {
    global 
           $nm_saida, $nm_url_saida;
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['vipguests_id'] = "VI Pguests Id";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['events_id'] = "Events Id";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['organisation'] = "Organisation";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['photo'] = "Photo";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['name'] = "Name";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['contactnumber'] = "Contact Number";
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['labels']['email'] = "Email";
   $HTTP_REFERER = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : ""; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['seq_dir'] = 0; 
   $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['sub_dir'] = array(); 
   $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_orig'];
   $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq'];
   $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['where_pesq_filtro'];
   if (isset($_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['lig_edit']) && $_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['lig_edit'] != '')
   {
       $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['mostra_edit'] = $_SESSION['scriptcase']['sc_apl_conf']['pdfreport_VIPguests']['lig_edit'];
   }
   if (!empty($this->nm_grid_sem_reg))
   {
       $this->Pdf_init();
       $this->Pdf->AddPage();
       if ($this->Font_ttf_sr)
       {
           $this->Pdf->SetFont($this->default_font_sr, 'B', 12, $this->def_TTF);
       }
       else
       {
           $this->Pdf->SetFont($this->default_font_sr, 'B', 12);
       }
       $this->Pdf->SetTextColor(0, 0, 0);
       $this->Pdf->Text(10, 10, html_entity_decode($this->nm_grid_sem_reg, ENT_COMPAT, $_SESSION['scriptcase']['charset']));
       $this->Pdf->Output($this->Ini->root . $this->Ini->nm_path_pdf, 'F');
       return;
   }
// 
   $Init_Pdf = true;
   $this->SC_seq_register = 0; 
   while (!$this->rs_grid->EOF) 
   {  
      $this->nm_grid_colunas = 0; 
      $nm_quant_linhas = 0;
      $this->Pdf->setImageScale(1.33);
      $this->Pdf->AddPage();
      $this->Pdf_init();
      $this->Pdf_image();
      while (!$this->rs_grid->EOF && $nm_quant_linhas < $_SESSION['sc_session'][$this->Ini->sc_page]['pdfreport_VIPguests']['qt_col_grid']) 
      {  
          $this->sc_proc_grid = true;
          $this->SC_seq_register++; 
          $this->vipguests_id[$this->nm_grid_colunas] = $this->rs_grid->fields[0] ;  
          $this->vipguests_id[$this->nm_grid_colunas] = (string)$this->vipguests_id[$this->nm_grid_colunas];
          $this->events_id[$this->nm_grid_colunas] = $this->rs_grid->fields[1] ;  
          $this->events_id[$this->nm_grid_colunas] = (string)$this->events_id[$this->nm_grid_colunas];
          $this->organisation[$this->nm_grid_colunas] = $this->rs_grid->fields[2] ;  
          $this->photo[$this->nm_grid_colunas] = $this->rs_grid->fields[3] ;  
          $this->name[$this->nm_grid_colunas] = $this->rs_grid->fields[4] ;  
          $this->contactnumber[$this->nm_grid_colunas] = $this->rs_grid->fields[5] ;  
          $this->email[$this->nm_grid_colunas] = $this->rs_grid->fields[6] ;  
          $this->vipguests_id[$this->nm_grid_colunas] = sc_strip_script($this->vipguests_id[$this->nm_grid_colunas]);
          if ($this->vipguests_id[$this->nm_grid_colunas] === "") 
          { 
              $this->vipguests_id[$this->nm_grid_colunas] = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($this->vipguests_id[$this->nm_grid_colunas], $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
          $this->vipguests_id[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->vipguests_id[$this->nm_grid_colunas]);
          $this->events_id[$this->nm_grid_colunas] = sc_strip_script($this->events_id[$this->nm_grid_colunas]);
          if ($this->events_id[$this->nm_grid_colunas] === "") 
          { 
              $this->events_id[$this->nm_grid_colunas] = "" ;  
          } 
          else    
          { 
              nmgp_Form_Num_Val($this->events_id[$this->nm_grid_colunas], $_SESSION['scriptcase']['reg_conf']['grup_num'], $_SESSION['scriptcase']['reg_conf']['dec_num'], "0", "S", "2", "", "N:" . $_SESSION['scriptcase']['reg_conf']['neg_num'] , $_SESSION['scriptcase']['reg_conf']['simb_neg'], $_SESSION['scriptcase']['reg_conf']['num_group_digit']) ; 
          } 
          $this->events_id[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->events_id[$this->nm_grid_colunas]);
          $this->organisation[$this->nm_grid_colunas] = sc_strip_script($this->organisation[$this->nm_grid_colunas]);
          if ($this->organisation[$this->nm_grid_colunas] === "") 
          { 
              $this->organisation[$this->nm_grid_colunas] = "" ;  
          } 
          if ($this->organisation[$this->nm_grid_colunas] !== "") 
          { 
              $this->organisation[$this->nm_grid_colunas] = sc_strtoupper($this->organisation[$this->nm_grid_colunas]); 
          } 
          $this->organisation[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->organisation[$this->nm_grid_colunas]);
          $this->photo[$this->nm_grid_colunas] = $this->photo[$this->nm_grid_colunas]; 
          if (empty($this->photo[$this->nm_grid_colunas]) || $this->photo[$this->nm_grid_colunas] == "none" || $this->photo[$this->nm_grid_colunas] == "*nm*") 
          { 
              $this->photo[$this->nm_grid_colunas] = "" ;  
              $out_photo = "" ; 
          } 
          elseif ($this->Ini->Gd_missing)
          { 
              $this->photo[$this->nm_grid_colunas] = "<span class=\"scErrorLine\">" . $this->Ini->Nm_lang['lang_errm_gd'] . "</span>";
          } 
          else   
          { 
              $out_photo = $this->Ini->path_imag_temp . "/sc_photo_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg";   
              $arq_photo = fopen($this->Ini->root . $out_photo, "w") ;  
              $_SESSION['scriptcase']['sc_num_img']++ ; 
              if (substr($this->photo[$this->nm_grid_colunas], 0, 4) == "*nm*") 
              { 
                  $this->photo[$this->nm_grid_colunas] = substr($this->photo[$this->nm_grid_colunas], 4) ; 
                  $this->photo[$this->nm_grid_colunas] = base64_decode($this->photo[$this->nm_grid_colunas]) ; 
              } 
              $img_pos_bm = strpos($this->photo[$this->nm_grid_colunas], "BM") ; 
              if (!$img_pos_bm === FALSE && $img_pos_bm == 78) 
              { 
                  $this->photo[$this->nm_grid_colunas] = substr($this->photo[$this->nm_grid_colunas], $img_pos_bm) ; 
              } 
              fwrite($arq_photo, $this->photo[$this->nm_grid_colunas]) ;  
              fclose($arq_photo) ;  
              $SC_tp_img = $this->SC_type_img($this->Ini->root . $out_photo);
              if ($SC_tp_img != ".jpg") 
              { 
                  $out_photo = $this->Ini->path_imag_temp . "/sc_photo_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . $SC_tp_img ;   
                  $arq_photo = fopen($this->Ini->root . $out_photo, "w") ;  
                  fwrite($arq_photo, $this->photo[$this->nm_grid_colunas]) ;  
                  fclose($arq_photo) ;  
              }
              $out1_photo = $out_photo; 
              $out_photo = $this->Ini->path_imag_temp . "/" . "photo_" . $_SESSION['scriptcase']['sc_num_img'] . session_id() . ".jpg" ; 
              $_SESSION['scriptcase']['sc_num_img']++ ; 
              $sc_obj_img = new nm_trata_img($this->Ini->root . $out1_photo);
              $sc_obj_img->setWidth(300);
              $sc_obj_img->setHeight(200);
              $sc_obj_img->setManterAspecto(true);
              $sc_obj_img->createImg($this->Ini->root . $out_photo);
              $this->photo[$this->nm_grid_colunas] = $this->NM_raiz_img . $out_photo; 
          } 
          $this->name[$this->nm_grid_colunas] = sc_strip_script($this->name[$this->nm_grid_colunas]);
          if ($this->name[$this->nm_grid_colunas] === "") 
          { 
              $this->name[$this->nm_grid_colunas] = "" ;  
          } 
          $this->name[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->name[$this->nm_grid_colunas]);
          $this->contactnumber[$this->nm_grid_colunas] = sc_strip_script($this->contactnumber[$this->nm_grid_colunas]);
          if ($this->contactnumber[$this->nm_grid_colunas] === "") 
          { 
              $this->contactnumber[$this->nm_grid_colunas] = "" ;  
          } 
          $this->contactnumber[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->contactnumber[$this->nm_grid_colunas]);
          $this->email[$this->nm_grid_colunas] = sc_strip_script($this->email[$this->nm_grid_colunas]);
          if ($this->email[$this->nm_grid_colunas] === "") 
          { 
              $this->email[$this->nm_grid_colunas] = "" ;  
          } 
          $this->email[$this->nm_grid_colunas] = $this->SC_conv_utf8($this->email[$this->nm_grid_colunas]);
            $cell_organisation = array('posx' => 108, 'posy' => 49, 'data' => $this->organisation[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 16, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_name = array('posx' => 108, 'posy' => 55, 'data' => $this->name[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 16, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);
            $cell_photo = array('posx' => 117, 'posy' => 67, 'data' => $this->photo[$this->nm_grid_colunas], 'width'      => 0, 'align'      => 'L', 'font_type'  => $this->default_font, 'font_size'  => 16, 'color_r'    => '0', 'color_g'    => '0', 'color_b'    => '0', 'font_style' => $this->default_style);


            $this->Pdf->SetFont($cell_organisation['font_type'], $cell_organisation['font_style'], $cell_organisation['font_size']);
            $this->pdf_text_color($cell_organisation['data'], $cell_organisation['color_r'], $cell_organisation['color_g'], $cell_organisation['color_b']);
            if (!empty($cell_organisation['posx']) && !empty($cell_organisation['posy']))
            {
                $this->Pdf->SetXY($cell_organisation['posx'], $cell_organisation['posy']);
            }
            elseif (!empty($cell_organisation['posx']))
            {
                $this->Pdf->SetX($cell_organisation['posx']);
            }
            elseif (!empty($cell_organisation['posy']))
            {
                $this->Pdf->SetY($cell_organisation['posy']);
            }
            $this->Pdf->Cell($cell_organisation['width'], 0, $cell_organisation['data'], 0, 0, $cell_organisation['align']);

            $this->Pdf->SetFont($cell_name['font_type'], $cell_name['font_style'], $cell_name['font_size']);
            $this->pdf_text_color($cell_name['data'], $cell_name['color_r'], $cell_name['color_g'], $cell_name['color_b']);
            if (!empty($cell_name['posx']) && !empty($cell_name['posy']))
            {
                $this->Pdf->SetXY($cell_name['posx'], $cell_name['posy']);
            }
            elseif (!empty($cell_name['posx']))
            {
                $this->Pdf->SetX($cell_name['posx']);
            }
            elseif (!empty($cell_name['posy']))
            {
                $this->Pdf->SetY($cell_name['posy']);
            }
            $this->Pdf->Cell($cell_name['width'], 0, $cell_name['data'], 0, 0, $cell_name['align']);

            if (isset($cell_photo['data']) && !empty($cell_photo['data']) && is_file($cell_photo['data']))
            {
                $this->Pdf->Image($cell_photo['data'], $cell_photo['posx'], $cell_photo['posy'], 0, 0);
            }
          $max_Y = 0;
          $this->rs_grid->MoveNext();
          $this->sc_proc_grid = false;
          $nm_quant_linhas++ ;
      }  
   }  
   $this->rs_grid->Close();
   $this->Pdf->Output($this->Ini->root . $this->Ini->nm_path_pdf, 'F');
 }
 function pdf_text_color(&$val, $r, $g, $b)
 {
     $pos = strpos($val, "@SCNEG#");
     if ($pos !== false)
     {
         $cor = trim(substr($val, $pos + 7));
         $val = substr($val, 0, $pos);
         $cor = (substr($cor, 0, 1) == "#") ? substr($cor, 1) : $cor;
         if (strlen($cor) == 6)
         {
             $r = hexdec(substr($cor, 0, 2));
             $g = hexdec(substr($cor, 2, 2));
             $b = hexdec(substr($cor, 4, 2));
         }
     }
     $this->Pdf->SetTextColor($r, $g, $b);
 }
 function SC_conv_utf8($input)
 {
     if ($_SESSION['scriptcase']['charset'] != "UTF-8" && !NM_is_utf8($input))
     {
         $input = sc_convert_encoding($input, "UTF-8", $_SESSION['scriptcase']['charset']);
     }
     return $input;
 }
   function nm_conv_data_db($dt_in, $form_in, $form_out)
   {
       $dt_out = $dt_in;
       if (strtoupper($form_in) == "DB_FORMAT")
       {
           if ($dt_out == "null" || $dt_out == "")
           {
               $dt_out = "";
               return $dt_out;
           }
           $form_in = "AAAA-MM-DD";
       }
       if (strtoupper($form_out) == "DB_FORMAT")
       {
           if (empty($dt_out))
           {
               $dt_out = "null";
               return $dt_out;
           }
           $form_out = "AAAA-MM-DD";
       }
       nm_conv_form_data($dt_out, $form_in, $form_out);
       return $dt_out;
   }
    function SC_type_img($img_file)
    {
        $type_img = getimagesize($img_file);
        if (!$type_img)
        {
            return (".jpg");
        }
        switch ($type_img[2])
        {
           case 1:  return ".gif";   break;
           case 2:  return ".jpg";   break;
           case 3:  return ".png";   break;
           case 4:  return ".swf";   break;
           case 5:  return ".psd";   break;
           case 6:  return ".bmp";   break;
           case 7:  return ".tiff";  break;
           case 8:  return ".tiff";  break;
           case 9:  return ".jpc";   break;
           case 10: return ".jp2";   break;
           case 11: return ".jpx";   break;
           case 12: return ".jb2";   break;
           case 13: return ".swc";   break;
           case 14: return ".ief";   break;
           case 15: return ".wbmp";  break;
           case 16: return ".xbm";   break;
           default: return ".jpg";   break;
        }
    }

   function nm_gera_mask(&$nm_campo, $nm_mask)
   { 
      $trab_campo = $nm_campo;
      $trab_mask  = $nm_mask;
      $tam_campo  = strlen($nm_campo);
      $trab_saida = "";
      $mask_num = false;
      for ($x=0; $x < strlen($trab_mask); $x++)
      {
          if (substr($trab_mask, $x, 1) == "#")
          {
              $mask_num = true;
              break;
          }
      }
      if ($mask_num )
      {
          $ver_duas = explode(";", $trab_mask);
          if (isset($ver_duas[1]) && !empty($ver_duas[1]))
          {
              $cont1 = count(explode("#", $ver_duas[0])) - 1;
              $cont2 = count(explode("#", $ver_duas[1])) - 1;
              if ($cont2 >= $tam_campo)
              {
                  $trab_mask = $ver_duas[1];
              }
              else
              {
                  $trab_mask = $ver_duas[0];
              }
          }
          $tam_mask = strlen($trab_mask);
          $xdados = 0;
          for ($x=0; $x < $tam_mask; $x++)
          {
              if (substr($trab_mask, $x, 1) == "#" && $xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_campo, $xdados, 1);
                  $xdados++;
              }
              elseif ($xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_mask, $x, 1);
              }
          }
          if ($xdados < $tam_campo)
          {
              $trab_saida .= substr($trab_campo, $xdados);
          }
          $nm_campo = $trab_saida;
          return;
      }
      for ($ix = strlen($trab_mask); $ix > 0; $ix--)
      {
           $char_mask = substr($trab_mask, $ix - 1, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               $trab_saida = $char_mask . $trab_saida;
           }
           else
           {
               if ($tam_campo != 0)
               {
                   $trab_saida = substr($trab_campo, $tam_campo - 1, 1) . $trab_saida;
                   $tam_campo--;
               }
               else
               {
                   $trab_saida = "0" . $trab_saida;
               }
           }
      }
      if ($tam_campo != 0)
      {
          $trab_saida = substr($trab_campo, 0, $tam_campo) . $trab_saida;
          $trab_mask  = str_repeat("z", $tam_campo) . $trab_mask;
      }
   
      $iz = 0; 
      for ($ix = 0; $ix < strlen($trab_mask); $ix++)
      {
           $char_mask = substr($trab_mask, $ix, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               if ($char_mask == "." || $char_mask == ",")
               {
                   $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
               }
               else
               {
                   $iz++;
               }
           }
           elseif ($char_mask == "x" || substr($trab_saida, $iz, 1) != "0")
           {
               $ix = strlen($trab_mask) + 1;
           }
           else
           {
               $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
           }
      }
      $nm_campo = $trab_saida;
   } 
}
?>
