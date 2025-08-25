$(function () {
    $('.select2-vendor').select2({
        placeholder: "Select Vendor",
        allowClear: true
    });

    $('.select2-customer').select2({
        placeholder: "Select Customer",
        allowClear: true
    });

    $('#main_group').change(function () {
        const mainGroup = $(this).val();
        resetAccountHead();
        $('#sub1_group').html('<option value="">Select Sub 1 Group</option>');
        $('#sub2_group').html('<option value="">Select Sub 2 Group</option>');
        $('#detailed_group').html('<option value="">Select Detailed Group</option>');

        if (mainGroup === 'Balance Sheet') {
            $('#sub1_group').append(`
                                <option value="Equity">Equity</option>
                                <option value="Liabilities">Liabilities</option>
                                <option value="Assets">Assets</option>
                            `);
        } else if (mainGroup === 'Profit And Loss Account') {
            $('#sub1_group').append(`
                                <option value="Incomes">Incomes</option>
                                <option value="Expenses">Expenses</option>
                            `);
        }

        $('#vendor_section, #customer_section').addClass('hidden');
        $('#vendor_id, #customer_id').removeAttr('required');
    });

    $('#sub1_group').change(function () {
        const mainGroup = $('#main_group').val();
        const sub1Group = $(this).val();
        resetAccountHead();
        $('#sub2_group').html('<option value="">Select Sub 2 Group</option>');
        $('#detailed_group').html('<option value="">Select Detailed Group</option>');

        if (mainGroup === 'Balance Sheet') {
            if (sub1Group === 'Equity') {
                $('#sub2_group').append(`
                                    <option value="Equity">Equity</option>
                                `);
            } else if (sub1Group === 'Liabilities') {
                $('#sub2_group').append(`
                                    <option value="Non Current Liabilities">Non Current Liabilities</option>
                                    <option value="Current Liabilities">Current Liabilities</option>
                                `);
            } else if (sub1Group === 'Assets') {
                $('#sub2_group').append(`
                                    <option value="Non Current Assets">Non Current Assets</option>
                                    <option value="Current Assets">Current Assets</option>
                                `);
            }
        } else if (mainGroup === 'Profit And Loss Account') {
            if (sub1Group === 'Incomes') {
                $('#sub2_group').append(`
                                    <option value="Sales">Sales</option>
                                `);
            } else if (sub1Group === 'Expenses') {
                $('#sub2_group').append(`
                                    <option value="Cost of sales">Cost of sales</option>
                                    <option value="Admin Expenses">Admin Expenses</option>
                                    <option value="Financial Charges">Financial Charges</option>
                                    <option value="Selling Expenses">Selling Expenses</option>
                                    <option value="Other Expenses">Other Expenses</option>
                                    <option value="Taxation">Taxation</option>
                                `);
            }
        }

        $('#vendor_section, #customer_section').addClass('hidden');
        $('#vendor_id, #customer_id').removeAttr('required');
    });

    // Sub 2 Group Change Event
    $('#sub2_group').change(function () {
        const mainGroup = $('#main_group').val();
        const sub1Group = $('#sub1_group').val();
        const sub2Group = $(this).val();
        resetAccountHead();
        $('#detailed_group').html('<option value="">Select Detailed Group</option>');

        if (mainGroup === 'Balance Sheet') {
            if (sub1Group === 'Equity' && sub2Group === 'Equity') {
                $('#detailed_group').append(`
                                    <option value="Capital Account">Capital Account</option>
                                    <option value="Current Account">Current Account</option>
                                `);
            } else if (sub1Group === 'Liabilities') {
                if (sub2Group === 'Non Current Liabilities') {
                    $('#detailed_group').append(`
                                        <option value="Long term liabilities">Long term liabilities</option>
                                        <option value="Long Term Finance Lease">Long Term Finance Lease</option>
                                    `);
                } else if (sub2Group === 'Current Liabilities') {
                    $('#detailed_group').append(`
                                        <option value="Short Term Liabilities">Short Term Liabilities</option>
                                        <option value="Short Term Finance Lease">Short Term Finance Lease</option>
                                        <option value="Trade Creditors">Trade Creditors</option>
                                        <option value="Advances And Other Payables">Advances And Other Payables</option>
                                        <option value="Taxes Payable">Taxes Payable</option>
                                    `);
                }
            } else if (sub1Group === 'Assets') {
                if (sub2Group === 'Non Current Assets') {
                    $('#detailed_group').append(`
                                        <option value="Property Plant And Equipment">Property Plant And Equipment</option>
                                        <option value="Intangible Assets">Intangible Assets</option>
                                        <option value="Long Term Deposits And Pre-Payments">Long Term Deposits And Pre-Payments</option>
                                    `);
                } else if (sub2Group === 'Current Assets') {
                    $('#detailed_group').append(`
                                        <option value="Stores And Stock in Hand">Stores And Stock in Hand</option>
                                        <option value="Trade Debtors">Trade Debtors</option>
                                        <option value="Short Term Advances">Short Term Advances</option>
                                        <option value="Other Receivable">Other Receivable</option>
                                        <option value="Cash And Bank Balance">Cash And Bank Balance</option>
                                    `);
                }
            }
        } else if (mainGroup === 'Profit And Loss Account') {
            if (sub1Group === 'Incomes' && sub2Group === 'Sales') {
                $('#detailed_group').append(`
                                    <option value="Sales">Sales</option>
                                `);
            } else if (sub1Group === 'Expenses') {
                if (sub2Group === 'Cost of sales') {
                    $('#detailed_group').append(`
                                        <option value="Cost of sales">Cost of sales</option>
                                    `);
                } else if (sub2Group === 'Admin Expenses') {
                    $('#detailed_group').append(`
                                        <option value="Admin Expenses">Admin Expenses</option>
                                    `);
                } else if (sub2Group === 'Financial Charges') {
                    $('#detailed_group').append(`
                                        <option value="Financial Charges">Financial Charges</option>
                                    `);
                } else if (sub2Group === 'Selling Expenses') {
                    $('#detailed_group').append(`
                                        <option value="Selling Expenses">Selling Expenses</option>
                                    `);
                } else if (sub2Group === 'Other Expenses') {
                    $('#detailed_group').append(`
                                        <option value="Other Expenses">Other Expenses</option>
                                    `);
                } else if (sub2Group === 'Taxation') {
                    $('#detailed_group').append(`
                                        <option value="Taxation">Taxation</option>
                                    `);
                }
            }
        }

        $('#vendor_section, #customer_section').addClass('hidden');
        $('#vendor_id, #customer_id').removeAttr('required');
    });

    // Detailed Group Change Event
    $('#detailed_group').change(function () {
        const detailedGroup = $(this).val();
        resetAccountHead();
        if (detailedGroup === 'Trade Creditors') {
            $('#vendor_section').removeClass('hidden');
            $('#customer_section').addClass('hidden');
            $('#vendor_id').attr('required', 'required');
            $('#customer_id').removeAttr('required');
            setTimeout(function () {
                $('.select2-vendor').select2({
                    placeholder: "Select Vendor",
                    allowClear: true
                });
            }, 100);
        } else if (detailedGroup === 'Trade Debtors') {
            $('#customer_section').removeClass('hidden');
            $('#vendor_section').addClass('hidden');
            $('#customer_id').attr('required', 'required');
            $('#vendor_id').removeAttr('required');

            setTimeout(function () {
                $('.select2-customer').select2({
                    placeholder: "Select Customer",
                    allowClear: true
                });
            }, 100);
        } else {
            $('#vendor_section, #customer_section').addClass('hidden');
            $('#vendor_id, #customer_id').removeAttr('required');
        }
    });

    $('#chartOfAccountForm').submit(function (e) {
        if ($('#vendor_section').is(':visible') && !$('#vendor_id').val()) {
            e.preventDefault();
            alert('Please select a vendor');
            return false;
        }

        if ($('#customer_section').is(':visible') && !$('#customer_id').val()) {
            e.preventDefault();
            alert('Please select a customer');
            return false;
        }
    });

    $('#vendor_id').change(function () {
        $('#account_head').val('');
        $('#account_head').attr('readonly', true);
        let name = $('#vendor_id option:selected').data('name');
        $('#account_head').val(name);

        $('#account_head').addClass('highlight-bg');
    });

    $('#customer_id').change(function () {
        $('#account_head').val('');
        $('#account_head').attr('readonly', true);
        let name = $('#customer_id option:selected').data('name');
        $('#account_head').val(name);

        $('#account_head').addClass('highlight-bg');
    });

    function resetAccountHead() {
        $('#account_head').val('');
        $('#customer_id').val('');
        $('#vendor_id').val('');
        $('#account_head').removeAttr('readonly');
        $('#account_head').removeClass('highlight-bg');
    }
});