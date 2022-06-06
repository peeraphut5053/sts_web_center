/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function CreateDataGrid() {
    for (i = 0; i < 300; i++) {
        $(".grid-container").append(`<div class='grid-items position-${i}'></div>`);
        if (i == '51' || i == '53' || i == '61' || i == '195' || i == '227') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8597;</div>`);
        } else if (i == '37' || i == '79' || i == '105') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8596;</div>`);
        } else if (i == '45' || i == '47' || i == '69' || i == '71' || i == '73' || i == '103') {
            $(`.position-${i}`).append(`<div class="arrow">&#8594;</div>`);
        } else if (i == '217' || i == '214') {
            $(`.position-${i}`).append(`<div class="arrow">&#8592;</div>`);
        } else if (i == '89' || i == '229') {
            $(`.position-${i}`).append(`<div class="arrow">&#8593;</div>`);
        } else if (i == '27' || i == '65' || i == '93' || i == '95' || i == '99' || i == '129' || i == '133' || i == '201') {
            $(`.position-${i}`).append(`<div class="arrow">&#8595;</div>`);
        } else if (i == '-1') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8601;</div>`);
        } else if (i == '26' || i == '92') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8600;</div>`);
        } else if (i == '128' || i == '230') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8598;</div>`);
        } else if (i == '228') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8599;</div>`);
        } else if (i == '28') {
            $(`.position-${i}`).append(`<div class="arrow ">&#8601;</div>`);
        }
    }
}

function ProcessOfWork() {
    CreateDataGrid();
    data = {
        boatNote:
                {
                    module_name: 'Boat Note',
                    link: ['link2', 'link3'],
                    desc: 'การขนส่งสินค้าขึ้นเรือ'
                },
        Customer:
                {
                    module_name: 'Customer',
                    link: ['ลูกต้าในประเทศ', 'ลูกต้าต่างประเทศ', 'ลูกค้าทั้งหมด'],
                    desc: 'ข้อมูลลูกค้า'
                },
        CustomerShipTo:
                {
                    module_name: 'Customer Ship To',
                    link: [],
                    desc: 'การส่งสินค้าถึงลูกค้า'
                },
        CustomerOrder:
                {
                    module_name: 'Customer Order',
                    link: [],
                    desc: 'รายการสั่งของลูกค้า'
                },
        DeliveryOrder:
                {
                    module_name: 'Delivery Order',
                    link: [],
                    desc: 'รายการส่งของเพื่อจัดส่งลูกค้า'
                },
        Preship:
                {
                    module_name: 'Pre ship',
                    link: [],
                    desc: 'Process ก่อนส่งสินค้าถึงลูกค้า, ยกของขึ้นรถ ,AIT_PRESHIP'
                },
        OrderShipping:
                {
                    module_name: 'Order Shipping',
                    link: [],
                    desc: 'การนำใบ Do ที่เปิดไว้ไปส่ง'
                },
        BudgetPlan:
                {
                    module_name: 'Budget Plan',
                    link: [],
                    desc: 'การวางแผนงบประมาณ'
                }
    };

    //work bench  ปรับแต่งข้อมูลก่อน Post ดูส่วนลดต่างๆ
    //AP ทำ PO recevie แล้วมาเป็น AP คือเราซื้อ
    //AR คือเรารับ
    //journal คือเส้นทางของการเงิน ก่อนมาเป็น Ledger
    //item_whse
    console.log(data);
    processmodule(8, data.boatNote);
    processmodule(10, data.Customer)
    processmodule(12, data.CustomerShipTo)
    processmodule(44, data.CustomerOrder)
    processmodule(46, data.DeliveryOrder)
    processmodule(48, data.Preship)
    processmodule(34, data.BudgetPlan)
    processmodule(36, 'Vendor', 'card_vendor')
    processmodule(38, 'Vendor ADDR', 'card_vendor_address')
    processmodule(76, 'MISC RECEIPT', 'card_')
    processmodule(78, 'Job order', 'card_')
    processmodule(112, 'APS PLANNING', 'card_')
    processmodule(68, 'PR', 'card_pr')
    processmodule(70, 'PO', 'card_po')
    processmodule(72, 'GRN', 'card_grn')
    processmodule(74, 'PO RECEIVING', 'card_')
    processmodule(80, 'Barcode Webapp', 'card_')
    processmodule(82, data.OrderShipping)
    processmodule(110, 'Stock <br> Location', 'card_stock_location')
    processmodule(146, 'Job receive', 'card_job_receive')
    processmodule(150, 'CONSO GRN', 'card_job_receive')
    processmodule(102, 'Import Weight List', 'card_weight_list_imporrt')
    processmodule(104, 'Weight List', 'card_weight_list')
    processmodule(106, 'MAP GRN', 'card_map_grn')
    processmodule(116, 'INVOICING', 'card_')
    processmodule(144, 'MATERIAL<br> TRANSACTION', 'card_')
    processmodule(210, 'Journal', 'card_')
    processmodule(212, 'FINANCAL', 'card_')
    processmodule(216, 'CONSO INV', 'card_')
    processmodule(218, 'CONSO WORKBENCH', 'card_')
    processmodule(244, 'LEDGER', 'card_')
    processmodule(246, 'AR', 'card_')
    processmodule(248, 'AP', 'card_')
}
function processmodule(position, cardprop) {


    $(`.position-${position}`).append(`<a class="btn btn-primary btn-menu" data-toggle="collapse" data-target="#multiCardsCtrl-${position}" aria-expanded="false" aria-controls="multiCardsCtrl-${position}">${cardprop.module_name}</a>`);
    $(`.position-${position}`).append(`<div class="col">
                                            <div class="collapse multi-collapse" id="multiCardsCtrl-${position}">
                                                <div class='card card-body'>
                                                    <div class="details-content card_${position}" >
                                                        <div class="grid-container-card">
                                                            <div class="grid-items-card card-link card-link-${position} ">
                                                                    
                                                            </div>
                                                            <div class="grid-items-card card-desc">${cardprop.desc}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
    for (let i = 0; i < cardprop.link.length; i++) {
        $(`.card-link-${position}`).append(`<div><a><i class="fa fa fa-hand-o-right" aria-hidden="true"></i> ${cardprop.link[i]} </a><div>`);
    }
}
