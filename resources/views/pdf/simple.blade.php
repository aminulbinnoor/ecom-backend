<!DOCTYPE html>
<!-- https://www.verywellhealth.com/my-doctors-prescription-4-times-a-day-or-every-6-hours-1124041 -->
<html>
  <head>
    <style>

    html,
    body {
      padding: 0;
      margin: 0;
    }

      @page {
          header: html_otherpageheader;
          footer: html_otherpagesfooter;
      }
      @page :first {
          header: html_firstpageheader;
          footer: html_firstpagefooter;
      }
    </style>
  </head>
  <body>
    <htmlpageheader name="firstpageheader" style="display:none">
      <div style="padding: 15px;">
        <table style="width: 100%">
          <tbody>
            <tr style="border: 0px">
              <td style="border: 0px; padding: 2px">
                <img
                  src="https://team.p2p.com.bd/p-2-p-logo.png"
                  style="width: 115px"
                />
              </td>
              <td style="border: 0px; padding: 2px; width: 254px">
                <table style="margin-bottom: 0px; width: 100%">
                  <tr style="border: 0px; padding: 2px">
                    <td style="border: 0px; text-align: right">
                      <div style="font-size: 1.5em; font-weight: 600; color: #F68B1E;">
                        INVOICE
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td
                      style="
                        border: 0px;
                        font-size: 1.1em;
                        font-weight: 600;
                        text-align: right;"
                    >
                      No. {{$order->id}}
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </htmlpageheader>

    <htmlpagefooter name="firstpagefooter" style="display:none;">
      <div style="padding: 8px 15px; margin-bottom: 40px;">
          <table style="width: 100%;">
              <tbody>
                  <tr>
                      <td style="text-align: left;">
                          <div style="display: inline-block; font-size: 1.1em; border-top-style: dashed; border-top-width: 2px; padding-top: 4px; padding-left: 15px; padding-right: 15px;">Customer Signature</div>
                      </td>
                      <td style="text-align: right;">
                          <div style="display: inline-block; font-size: 1.1em; border-top-style: dashed; border-top-width: 2px; padding-top: 4px; padding-left: 15px; padding-right: 15px;">P2P authorized signature</div>
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>


    </htmlpagefooter>

    <htmlpageheader name="otherpageheader" line="4" style="display:none;">
      <div style="text-align:center">
        <div class="company" margin_bottom:20px;>
          <div>
            <h2 style="color: #F68B1E;">Invoice Number  - 100001</h2>
          </div>
        </div>
      </div>
    </htmlpageheader>

    <htmlpagefooter name="otherpagesfooter" style="display:none">
        <div style="text-align:left; border-top: 2px solid #F68B1E;">address: 105/2 nandoni 3rd floor, sukrabad,dhanmondi dhaka. website:https://thedoctorbd.xyz phone:2562526| page-{PAGENO}</div>
    </htmlpagefooter>

    <div style="padding: 8px 15px;">
      <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <thead>
          <tr>
            <th style="text-align: left; font-size: 1em; font-weight: 600; color:  #f68b1e; width: 40%;">Billed To:</th>
            <th style="text-align: center; font-size: 1em; font-weight: 600; color:  #f68b1e;">Date of Issue</th>
            <!-- <th style="text-align: center; font-size: 1em; font-weight: 600; color:  #f68b1e;">Advanced</th> -->
            <th style="text-align: right; font-size: 1em; font-weight: 600; color:  #f68b1e;">Amount Due</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding: 2px 0px; text-align: left;">{{$user->first_name}} {{$user->last_name ? $user->last_name : ''}}</td>
            <td style="padding: 2px 0px; text-align: center; ">{{$order->created_at}}</td>
            <!-- <td style="padding: 2px 0px; text-align: center; ">15,680 BDT</td> -->
            <td style="padding: 2px 0px; text-align: right;">{{$order->total}}</td>
          </tr>
          <tr>
            <td class="text-left" style="border: 0px">{{$user->phone}}</td>
          </tr>

          <tr>
            <td class="text-left" style="border: 0px">
              {{$user->address}}
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <div style="padding: 8px 15px; margin-bottom: 150px; margin-top: 25px;">
      <table
        border="0"
        cellspacing="0"
        cellpadding="0"
        style="width: 100%; border-top: 3px solid #f68b1e; border-collapse: collapse;
        border-spacing: 0;"
      >
        <thead>
          <tr>
            <th style="text-align: left; font-size: 1.1em; color:  #f68b1e; font-weight: 700; padding: 12px 8px;border-bottom: 1px solid #f68b1e;">Id</th>
            <th style="text-align: left; font-size: 1.1em; color:  #f68b1e; font-weight: 700; padding: 12px 8px;border-bottom: 1px solid #f68b1e;">Name</th>
            <th style="text-align: right; font-size: 1.1em; color: #f68b1e; font-weight: 700; padding: 12px 8px;border-bottom: 1px solid #f68b1e;">Rate</th>
            <th style="text-align: right; font-size: 1.1em; color: #f68b1e; font-weight: 700; padding: 12px 8px;border-bottom: 1px solid #f68b1e;">Qty</th>
            <th style="text-align: right; font-size: 1.1em; color: #f68b1e; font-weight: 700; padding: 12px 8px;border-bottom: 1px solid #f68b1e;">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td style="text-align: left; padding: 8px; border-bottom: 1px solid #f68b1e;">{{$product->product_id}}</td>
            <td style="text-align: left; padding: 8px; border-bottom: 1px solid #f68b1e;">{{$product->name}}</td>
            <td style="text-align: right; width: 20%;padding: 8px; border-bottom: 1px solid #f68b1e;">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$product->price}}
            </td>
            <td style="text-align: right;width: 10%;padding: 8px; border-bottom: 1px solid #f68b1e;">{{$product->quantity}}</td>
            <td style="text-align: right;width: 20%;padding: 8px; border-bottom: 1px solid #f68b1e;" class="total">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$product->price}}
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="1"></td>
            <td colspan="2" style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">Subtotal</td>
            <td style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$order->sub_total}}
            </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="2" style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">Delivery charge</td>
            <td style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$order->delivery_charge}}
            </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="2" style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">Discount</td>
            <td style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; border-bottom: 1px solid #f68b1e;">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$order->discount}}
            </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="2" style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px; ">Total</td>
            <td style="font-size: 1.1em; font-weight: 700;text-align: right; padding: 8px;">
              <img
                src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Taka_%28Bengali_letter%29.svg/252px-Taka_%28Bengali_letter%29.svg.png"
                style="width: 0.75em"
                alt=""
              />
              {{$order->total}}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

  </body>
</html>
