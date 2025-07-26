<div class="modal fade text-start modal-primary" id="showModal" tabindex="-1" aria-hidden="true" style="display: none;"
    wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel110">عرض تفاصيل الصنف {{ $name }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <table id="example1" class="table table-bordered table-striped dataTable" role="grid"
            aria-describedby="example1_info">
                {{-- item_code  اسم الصنف   --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">اسم الصنف</td>
                    <td>{{ $name }}</td>
                </tr>

                {{-- item_code  كود الصنف   --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">كود الصنف</td>
                    <td>{{ $item_code }}</td>
                </tr>


                {{-- order_type  نوع الفاتورة   --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">نوع الفاتورة</td>
                    <td>{{ $orderTypeLabel }}</td>
                </tr>


                {{-- item_units_id  وحدة الصنف المستلم بها الكمية   --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">وحدة الصنف المستلم بها الكمية</td>
                    <td>{{ $item_units_id }}</td>
                </tr>


                {{-- isMaster  نوع الوحدة  الصنف المستلم بها الكمية   --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">نوع الوحدة  الصنف المستلم بها الكمية</td>
                    <td>{{ $isMaster }}</td>
                </tr>


                {{-- itemType  نوع  الصنف    --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">نوع  الصنف </td>
                    <td>{{ $itemType }}</td>
                </tr>


                {{-- qty  كمية الصنف    --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">كمية الصنف </td>
                    <td>{{ $qty }}</td>
                </tr>


                 {{-- unit_price  سعر الوحدة    --}}
                 <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">سعر الوحدة </td>
                    <td>{{ $unit_price }}</td>
                </tr>


                {{-- total  الاجمالي    --}}
                <tr role="row">
                    <td style="width: 30%;background-color:#d6cacae6;">الاجمالي </td>
                    <td>{{ $total }}</td>
                </tr>

            </table>

        </div>
    </div>
</div>
