<script>
    function goBack() {
      window.history.back();
    }
</script>
<div style="margin-top:10px" id="printbutton">
    <center>
        <button href="#" class="btn btn-success " onclick="window.print()">Print</button>
        <button class="btn btn-success " onclick="getPDF()">Save as PDF</button>
        <button class="btn btn-success " onclick="getExcel()">Export to Excel</button>
        <br>
        <br>
    </center>
</div>
    <page size="Legal" class="canvas_div_pdf">
        <div style="padding:20px">
            <table width="100%" class="table-bsor table-bordsered" style="border-collapse: collapse;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td colspan="8" align="center">
                        <img class="logo-print m-t-10" src="<?php echo base_url().LOGO_IEMOP;?>">   
                    </td>
                    <td colspan="6"></td>           
                </tr>
                <tr>
                    <td colspan="20" align="center"> 
                        <h3 style="margin:0px">PAYMENT FORM</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant Name:</td>
                    <td colspan="10" class="bor-btm" align="center">CENTRAL NEGROS POWER RELIABILITY, INC. (CENPRI)</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant ID No.:</td>
                    <td colspan="10" class="bor-btm" align="center">1580</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="table-bordered font10" width="100%" >
                            <tr>
                                <td style="vertical-align: center;" width="9%" align="center"><b>Date of Payment</b></td>
                                <td style="vertical-align: center;" width="19%" align="center"><b>Invoice No.</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>VAT on Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Market Fees</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding Tax</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding VAT</b></td>
                                <td style="vertical-align: center;" width="11%" align="center"><b>Others (Prudential Requirement, Default Interest, etc.)</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>TOTAL</b></td> 
                            </tr>
                            <tr>
                                <td ></td>
                                <td class="xl94"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">-</td>
                            </tr>
                            <?php 
                                $x=1;
                                foreach($payment AS $p){ 
                            ?>
                            <?php if($x <= 50){ ?>
                            <tr>
                                <td align="center"><?php echo $p['transaction_date'];?></td>
                                <td align="center"><?php echo $p['reference_number'];?></td>
                                <td align="right">(<?php echo number_format($p['energy'],2);?>)</td>
                                <td align="right">(<?php echo number_format($p['vat_on_purchases'],2);?>)</td>
                                <td align="right"></td>
                                <td align="right"><?php echo number_format($p['ewt'],2);?></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">(<?php echo number_format($p['total_amount'],2);?>)</td>
                            </tr>
                            <?php }  $x++; } ?>
                            <?php if($x <= 50){ ?>
                            <tr>
                                <td align="center" colspan="2"><b>TOTAL AMOUNT PAID</b></td>
                                <td align="right"><b>(<?php echo number_format($energy,2);?>)</b></td>
                                <td align="right"><b>(<?php echo number_format($vat_on_purchases,2);?>)</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b><?php echo number_format($ewt,2);?></b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>(<?php echo number_format($total,2);?>)</b></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <?php if($x <= 50){ ?>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5"><b>Certified Correct:</b></td>
                    <td colspan="8" align="center" class="bor-btm" style="text-transform: capitalize;"><?php echo $_SESSION['fullname'];?></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="5"></b></td>
                    <td colspan="8" align="center">Authorized Representative & Signature</td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Payment Guide:</b></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="font10" width="100%">
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Standard Chartered Bank</b>  
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="20%">Account Name:</td>
                                <td width="50%"><b>Independent Electricity Market Operator of the Philippines, Inc.</b> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Beneficiary Account No.:</td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Account No.:</td>
                                <td><b>01-99492385786</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Swift Code:</td>
                                <td><b>SCBLPHMM</b></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Banco De Oro</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Company Name:</td>
                                <td><b>SCB FAO IEMOP</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Name.:</td>
                                <td><b>Your Company Name</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Account No.:  </td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">- For check payment, payee should be in the name of  <u><b>"SCB FAO IEMOP"</b></u></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Send this form together with transaction slip at <span style="color:red">accounts.management@iemop.ph</span></b></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </page>
    <?php if($x >= 50){ ?>
        <page size="Legal" class="canvas_div_pdf">
        <div style="padding:20px">
            <table width="100%" class="table-bsor table-bordsered" style="border-collapse: collapse;">
                <tr>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                    <td width="5%"></td>
                </tr>
                <!-- <tr>
                    <td colspan="6"></td>
                    <td colspan="8" align="center">
                        <img class="logo-print m-t-10" src="<?php echo base_url().LOGO_IEMOP;?>">   
                    </td>
                    <td colspan="6"></td>           
                </tr>
                <tr>
                    <td colspan="20" align="center"> 
                        <h3 style="margin:0px">PAYMENT FORM</h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant Name:</td>
                    <td colspan="10" class="bor-btm" align="center">CENTRAL NEGROS POWER RELIABILITY, INC. (CENPRI)</td>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5">Market Participant ID No.:</td>
                    <td colspan="10" class="bor-btm" align="center">1580</td>
                    <td colspan="5"></td>
                </tr>-->
                <tr>
                    <td colspan="20"><br></td>
                </tr> 
                <tr>
                    <td colspan="20">
                        <table class="table-bordered font10" width="100%" >
                            <tr>
                                <td style="vertical-align: center;" width="9%" align="center"><b>Date of Payment</b></td>
                                <td style="vertical-align: center;" width="19%" align="center"><b>Invoice No.</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>VAT on Energy</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Market Fees</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding Tax</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>Withholding VAT</b></td>
                                <td style="vertical-align: center;" width="11%" align="center"><b>Others (Prudential Requirement, Default Interest, etc.)</b></td>
                                <td style="vertical-align: center;" width="10%" align="center"><b>TOTAL</b></td> 
                            </tr>
                            <tr>
                                <td ></td>
                                <td class="xl94"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">-</td>
                            </tr>
                            <?php 
                                $y=1;
                                foreach($payment AS $p){
                            ?>
                            <?php if($y >= 51){ ?>
                            <tr>
                                <td align="center"><?php echo $p['transaction_date'];?></td>
                                <td align="center"><?php echo $p['reference_number'];?></td>
                                <td align="right">(<?php echo number_format($p['energy'],2);?>)</td>
                                <td align="right">(<?php echo number_format($p['vat_on_purchases'],2);?>)</td>
                                <td align="right"></td>
                                <td align="right"><?php echo number_format($p['ewt'],2);?></td>
                                <td align="right"></td>
                                <td align="right"></td>
                                <td align="right">(<?php echo number_format($p['total_amount'],2);?>)</td>
                            </tr>
                            <?php } $y++; } ?>
                            <?php if($x >= 51){ ?>
                            <tr>
                                <td align="center" colspan="2"><b>TOTAL AMOUNT PAID</b></td>
                                <td align="right"><b>(<?php echo number_format($energy,2);?>)</b></td>
                                <td align="right"><b>(<?php echo number_format($vat_on_purchases,2);?>)</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b><?php echo number_format($ewt,2);?></b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>-</b></td>
                                <td align="right"><b>(<?php echo number_format($total,2);?>)</b></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <?php if($y >= 51){ ?>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="5"><b>Certified Correct:</b></td>
                    <td colspan="8" align="center" class="bor-btm" style="text-transform: capitalize;"><?php echo $_SESSION['fullname'];?></td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="5"></b></td>
                    <td colspan="8" align="center">Authorized Representative & Signature</td>
                    <td colspan="7"></td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Payment Guide:</b></td>
                </tr>
                <tr>
                    <td colspan="20">
                        <table class="font10" width="100%">
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Standard Chartered Bank</b>  
                                </td>
                            </tr>
                            <tr>
                                <td width="10%"></td>
                                <td width="20%">Account Name:</td>
                                <td width="50%"><b>Independent Electricity Market Operator of the Philippines, Inc.</b> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Beneficiary Account No.:</td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Account No.:</td>
                                <td><b>01-99492385786</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Swift Code:</td>
                                <td><b>SCBLPHMM</b></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">
                                    <b>Banco De Oro</b>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Company Name:</td>
                                <td><b>SCB FAO IEMOP</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Name.:</td>
                                <td><b>Your Company Name</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Subscriber's Account No.:  </td>
                                <td><b>675006 xxxxxxxxxx</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2">- For check payment, payee should be in the name of  <u><b>"SCB FAO IEMOP"</b></u></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="20"><br></td>
                </tr>
                <tr style="border-top:1px dashed #000">
                    <td colspan="20"><br></td>
                </tr>
                <tr>
                    <td colspan="20"><b>Send this form together with transaction slip at <span style="color:red">accounts.management@iemop.ph</span></b></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </page>
    <?php } ?>
<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/export-excel.js"></script>

<script>
    function getPDF(){

        var HTML_Width = $(".canvas_div_pdf").width();
        var HTML_Height = $(".canvas_div_pdf").height();
        var top_left_margin = 10;
        var PDF_Width = HTML_Width+(top_left_margin*2);
        var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;
        var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
        html2canvas($(".canvas_div_pdf")[0],{allowTaint:true, 
            useCORS: true,
            logging: false,
            height: window.outerHeight + window.innerHeight,
            windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
            canvas.getContext('2d');
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            var check = document.getElementsByClassName('canvas_div_pdf');
            if (check.length > 1) {
                html2canvas($(".canvas_div_pdf")[1],{allowTaint:true, 
                    useCORS: true,
                    logging: false,
                    height: window.outerHeight + window.innerHeight,
                    windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
                    canvas.getContext('2d');
                    var imgData = canvas.toDataURL("image/jpeg", 1.0);
                    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
                    for (var i = 1; i <= totalPDFPages; i++) { 
                        pdf.addPage(PDF_Width, PDF_Height);
                        pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
                    }
                    pdf.save("Payment Form"+".pdf");
                });
            }else{
                pdf.save("Payment Form"+".pdf");
            }
        });
    };

    async function getExcel(filename = "Payment_Form.xlsx") {

        const pages = document.querySelectorAll(".canvas_div_pdf");
        if (!pages.length) {
            alert("No content found!");
            return;
        }

        const workbook = new ExcelJS.Workbook();
        const ws = workbook.addWorksheet("Payment Form");

        ws.pageSetup = {
            paperSize: 5, // Legal
            orientation: "portrait",
            fitToPage: true,
            fitToWidth: 1
        };

        let rowPointer = 1;

        /* ===== LOGO ===== */
        const logo = document.querySelector(".logo-print");
        if (logo) {
            const base64 = await toBase64(logo.src);
            const logoId = workbook.addImage({ base64, extension: "png" });

            ws.mergeCells("A1:I4");
            ws.addImage(logoId, {
                tl: { col: 3, row: 0.5 },
                ext: { width: 250, height: 70 }
            });

            rowPointer = 5;
        }

        /* ===== TITLE ===== */
        ws.mergeCells(`A${rowPointer}:I${rowPointer}`);
        ws.getCell(`A${rowPointer}`).value = "PAYMENT FORM";
        ws.getCell(`A${rowPointer}`).font = { bold: true, size: 12 };
        ws.getCell(`A${rowPointer}`).alignment = { horizontal: "center" };
        rowPointer += 2;

        // ===== Market Participant Name =====
        ws.mergeCells(`A${rowPointer}:B${rowPointer}`);
        ws.getCell(`A${rowPointer}`).value = "Market Participant Name:";
        ws.getCell(`A${rowPointer}`).font = { size: 12 };
        ws.getCell(`A${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`C${rowPointer}:G${rowPointer}`);
        const nameCell = ws.getCell(`C${rowPointer}`);
        nameCell.value = "CENTRAL NEGROS POWER RELIABILITY, INC. (CENPRI)";
        nameCell.font = { size: 12 };
        nameCell.alignment = { horizontal: "center" };
        nameCell.border = {
        bottom: { style: "thin", color: { argb: "FFBFBFBF" } }
        };

        rowPointer += 1;

        // ===== Market Participant ID =====
        ws.mergeCells(`A${rowPointer}:B${rowPointer}`);
        ws.getCell(`A${rowPointer}`).value = "Market Participant ID No.:";
        ws.getCell(`A${rowPointer}`).font = { size: 12 };
        ws.getCell(`A${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`C${rowPointer}:G${rowPointer}`);
        const idCell = ws.getCell(`C${rowPointer}`);
        idCell.value = "1580";
        idCell.font = { size: 12 };
        idCell.alignment = { horizontal: "center" };
        idCell.border = {
        bottom: { style: "thin", color: { argb: "FFBFBFBF" } }
        };

        rowPointer += 2; 

        const startRow = rowPointer;

        // ===== HEADERS =====
        const headers = [
            "Date of Payment",
            "Invoice No.",
            "Energy",
            "VAT on Energy",
            "Market Fees",
            "Withholding Tax",
            "Withholding VAT",
            "Others (Prudential Requirement, Default Interest, etc.)",
            "TOTAL"
        ];

        ws.columns = [
            { width: 15 }, // Date of Payment
            { width: 25 }, // Invoice No.
            { width: 16 }, // Energy
            { width: 16 }, // VAT on Energy
            { width: 16 }, // Market Fees
            { width: 16 }, // Withholding Tax
            { width: 16 }, // Withholding VAT
            { width: 16 }, // Others (Prudential Requirement, Default Interest, etc.)
            { width: 16 }  // TOTAL
        ];

        const headerRow = ws.getRow(startRow);
        headerRow.values = headers;

        headerRow.font = { size: 12, bold: true};
        headerRow.alignment = { wrapText:true, horizontal: "center", vertical: "middle" };
        headerRow.height = 80;

        headers.forEach((_, colIndex) => {
            const cell = headerRow.getCell(colIndex + 1); 
            cell.border = {
                top: { style: "thin" },
                left: { style: "thin" },
                bottom: { style: "thin" },
                right: { style: "thin" }
            };
        });

        rowPointer++;

        let totalEnergy = 0;
        let totalVatEnergy = 0;
        let totalMarketFees = 0;
        let totalEWT = 0;
        let totalTotal = 0;

        const parseAmount = val =>
        parseFloat(val.replace(/[(),]/g, "")) || 0;

        // ===== TABLE DATA ===== 
        pages.forEach(page => {
            const table = page.querySelector("table");
            if (!table) return;
            table.querySelectorAll("tr").forEach(tr => {
                if (tr.querySelectorAll("th").length) return;

                const cells = tr.querySelectorAll("td");
                if (cells.length !== 9) return;

                if (cells[0].innerText.trim() === "Date of Payment") return;

                const excelRow = ws.getRow(rowPointer);
                excelRow.values = Array.from(cells).map(td => td.innerText.trim());

                totalEnergy += parseAmount(cells[2].innerText);
                totalVatEnergy += parseAmount(cells[3].innerText);
                totalMarketFees += parseAmount(cells[4].innerText);
                totalEWT += parseAmount(cells[5].innerText);
                totalTotal += parseAmount(cells[8].innerText);

                for (let c = 1; c <= 9; c++) {
                    const cell = excelRow.getCell(c);
                    cell.alignment = {
                        horizontal: c <= 2 ? "center" : "right",
                        vertical: "middle"
                    };
                    cell.border = {
                        top: { style: "thin" },
                        left: { style: "thin" },
                        bottom: { style: "thin" },
                        right: { style: "thin" }
                    };
                }

                rowPointer++;
            });
        });

        // ===== TOTAL ROW =====
        const totalRow = ws.getRow(rowPointer);
        ws.mergeCells(`A${rowPointer}:B${rowPointer}`);
        totalRow.getCell(1).value = "TOTAL AMOUNT PAID";
        totalRow.getCell(3).value = `(${totalEnergy.toLocaleString(undefined, { minimumFractionDigits: 2 })})`;
        totalRow.getCell(4).value = `(${totalVatEnergy.toLocaleString(undefined, { minimumFractionDigits: 2 })})`;
        totalRow.getCell(5).value = `(${totalMarketFees.toLocaleString(undefined, { minimumFractionDigits: 2 })})`;
        totalRow.getCell(6).value = totalEWT.toLocaleString(undefined, { minimumFractionDigits: 2 });
        totalRow.getCell(7).value = "-";
        totalRow.getCell(8).value = "-";
        totalRow.getCell(9).value = `(${totalTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })})`;

        for (let c = 1; c <= 9; c++) {
            const cell = totalRow.getCell(c);
            cell.font = { bold: true };
            cell.alignment = {
                horizontal: c <= 2 ? "center" : "right",
                vertical: "middle"
            };
            cell.border = {
                top: { style: "thin" },
                left: { style: "thin" },
                bottom: { style: "thin" },
                right: { style: "thin" }
            };
        }

        rowPointer += 2

        // ===== Certified Correct =====
        ws.mergeCells(`A${rowPointer}:B${rowPointer}`);
        ws.getCell(`A${rowPointer}`).value = "Certified Correct:";
        ws.getCell(`A${rowPointer}`).font = { size: 12, bold: true };
        ws.getCell(`A${rowPointer}`).alignment = { horizontal: "left", vertical: "middle" };
        ws.getCell(`A${rowPointer}`).border = { top: { style: null }, left: { style: null }, bottom: { style: null }, right: { style: null } };

        ws.mergeCells(`C${rowPointer}:G${rowPointer}`);
        const idsCell = ws.getCell(`C${rowPointer}`);
        idsCell.value = "<?php echo $_SESSION['fullname'];?>";
        idsCell.font = { size: 12 };
        idsCell.alignment = { horizontal: "center" , vertical: "middle" };
        idsCell.border = {
            bottom: { style: "thin", color: { argb: "FFBFBFBF" } }
        };

        rowPointer += 1;

        ws.mergeCells(`C${rowPointer}:G${rowPointer}`);
        ws.getCell(`C${rowPointer}`).value = "Authorized Representative & Signature";
        ws.getCell(`C${rowPointer}`).font = { size: 12 };
        ws.getCell(`C${rowPointer}`).alignment = { horizontal: "center", vertical: "middle" };
        ws.getCell(`C${rowPointer}`).border = { top: { style: null }, left: { style: null }, bottom: { style: null }, right: { style: null } };

        rowPointer += 1; 

        ws.mergeCells(`A${rowPointer}:I${rowPointer}`);
        const idbCell = ws.getCell(`A${rowPointer}`);
        idbCell.value = "";
        idbCell.font = { size: 12 };
        idbCell.alignment = { horizontal: "center" , vertical: "middle" };
        idbCell.border = {
            bottom: { style: "dashed", color: { argb: "FF000000" } }
        };

        rowPointer += 2; 

        // ===== Payment Guide Title =====
        ws.mergeCells(`A${rowPointer}:I${rowPointer}`);
        ws.getCell(`A${rowPointer}`).value = "Payment Guide:";
        ws.getCell(`A${rowPointer}`).font = { size: 12, bold: true };
        ws.getCell(`A${rowPointer}`).alignment = { horizontal: "left", vertical: "middle" };

        rowPointer += 1;

        // ===== Standard Chartered Bank Section =====
        ws.mergeCells(`B${rowPointer}:I${rowPointer}`); 
        ws.getCell(`B${rowPointer}`).value = "Standard Chartered Bank";
        ws.getCell(`B${rowPointer}`).font = { bold: true };
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Account Name:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "Independent Electricity Market Operator of the Philippines, Inc.";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Beneficiary Account No.:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "675006 xxxxxxxxxx";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Account No.:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "01-99492385786";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Swift Code:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "SCBLPHMM";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 2;

        // ===== Banco De Oro Section =====
        ws.mergeCells(`B${rowPointer}:I${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Banco De Oro";
        ws.getCell(`B${rowPointer}`).font = { bold: true };
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Company Name:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "SCB FAO IEMOP";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Subscriber's Name.:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "Your Company Name";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        ws.mergeCells(`B${rowPointer}:C${rowPointer}`);
        ws.getCell(`B${rowPointer}`).value = "Subscriber's Account No.:";
        ws.getCell(`B${rowPointer}`).alignment = { horizontal: "left" };

        ws.mergeCells(`D${rowPointer}:H${rowPointer}`);
        ws.getCell(`D${rowPointer}`).value = "675006 xxxxxxxxxx";
        ws.getCell(`D${rowPointer}`).alignment = { horizontal: "left" };
        ws.getCell(`D${rowPointer}`).font = { bold: true };

        rowPointer += 1;

        // ===== Check payment =====
        ws.mergeCells(`B${rowPointer}:I${rowPointer}`);
        const checkCell = ws.getCell(`B${rowPointer}`);

        checkCell.value = {
            richText: [
                {
                    text: '- For check payment, payee should be in the name of '
                },
                {
                    text: '"SCB FAO IEMOP"',
                    font: {
                        bold: true,
                        underline: true
                    }
                }
            ]
        };

        checkCell.alignment = {
            horizontal: "left",
            wrapText: true
        };

        rowPointer += 1; 

        ws.mergeCells(`A${rowPointer}:I${rowPointer}`);
        const idcCell = ws.getCell(`A${rowPointer}`);
        idcCell.value = "";
        idcCell.font = { size: 12 };
        idcCell.alignment = { horizontal: "center" , vertical: "middle" };
        idcCell.border = {
            bottom: { style: "dashed", color: { argb: "FF000000" } }
        };

        rowPointer += 2; 



        // ===== Send this form =====
        ws.mergeCells(`A${rowPointer}:I${rowPointer}`);
        const emailCell = ws.getCell(`A${rowPointer}`);

        emailCell.value = {
            richText: [
                {
                    text: 'Send this form together with transaction slip at ',
                    font: {
                        bold: true
                    }
                },
                {
                    text: 'accounts.management@iemop.ph',
                    font: {
                        bold: true,
                        color: { argb: "FFFF0000" }
                    }
                }
            ]
        };

        emailCell.alignment = {
            horizontal: "left",
            wrapText: true
        };

        rowPointer += 1;

        // ===== DOWNLOAD ===== 
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    /* ===== IMAGE TO BASE64 ===== */
    function toBase64(url) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = "anonymous";
            img.onload = () => {
                const canvas = document.createElement("canvas");
                canvas.width = img.width;
                canvas.height = img.height;
                canvas.getContext("2d").drawImage(img, 0, 0);
                resolve(canvas.toDataURL("image/png"));
            };
            img.onerror = reject;
            img.src = url;
        });
    }

</script>
   
