<?php namespace Illuminate\Database\Seeder;

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
// use Laracasts\TestDummy\Factory as TestDummy;
use ECEPharmacyTree\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
    	Product::where('id', '>', 0)->delete();

        $this->insertProduct(24, 'DAPHNE TAB 500MCG 28', 'Lynestrenol', 'Indication: Contraception & in cases of intolerance to estrogen-progestogen combined pills or when estrogens are contraindicated. Also indicated in lactating mothers & smoking women who require contraception. \n Dosaging: For dosage information of prescription medicine, please consult with your doctor.', 0, 59.04, 95, 'tablet', 'box', 28, generate_sku());
        $this->insertProduct(12, 'PIMAX 200MCG ENTERIC-COATED TAB 30S', 'Tamsulosin HCl', 'Indications: Treatment of benign prostatic hyperplasia to relieve symptoms of urinary obstruction. \n Dosage: 200/400 mcg once daily.', 1, 60, 420, 'tablet', 'box', 30, generate_sku());
        $this->insertProduct(17, 'ENERAPLUS DROPS 15ML', 'Multivitamins', 'Indication: For the treatment of Ener A Plus Drops (Vitamin A (Vitamin A Palmitate)) deficiency.', 0, 30.2, 77.81, 'bottle', 'box', 1, generate_sku());
        $this->insertProduct(24, 'DAPHNE', 'Lynestrenol', 'Indications  Contraception, especially in cases of intolerance from estrogen-progestogen combined pills or when estrogens are contraindicated. Daphne is also indicated in lactating mothers and smoking women who require contraception.
            Dosage & Administration Take 1 tab daily without interruption, beginning on the 1st day of menstrual bleeding or start any day as long as there is no pregnancy but use condom for the next 7 days. Take the tablet marked with the corresponding day of the week. Take 1 tab about the same time everyday, preferably at about the time of the evening meal for 28 days.
            Medical and gynecological examinations should be performed before starting Daphne.
            Start taking the tablet orally on the 1st day of menstrual period, 6 weeks after giving birth or any day as long as not pregnant but use condom for the next 7 days. Take the tablet marked with the corresponding day of the week.
            Take 1 tab daily and without interruptions at about the same time everyday. It is easier to remember to take the tablets after eating meals or drinking tea or before brushing teeth or going to sleep. Follow the arrows indicated on the pack. The interval between 2 tab should be exactly 24 hrs as much as possible. If a tablet was missed, it must be taken within 3 hrs. The efficacy of the tablet may decrease if not taken within this period. Take the remaining tablets as scheduled to avoid premature withdrawal bleeding.
            Continue taking the pill for 28 days. Start a new pack the next day after taking the last tablet in the pack regardless of whether menstrual bleeding has ceased or not. Normal menstrual period will most likely resume 2 days after finishing the tablets in the pack.
            Continue taking the pill even if there is a brief pause from sexual intercourse. Suspend taking the pill only if there is no sexual intercourse for >3 months. Stop taking the pill only after finishing the present pack; otherwise, bleeding will come sooner.
            Continue taking the pill as long as contraception is desired.
            Missed Tablets: It is very important to take the pills regularly to prevent pregnancy.
            If 1 tab was missed by ≥3 hrs, take the missed tablet as soon as remembered. Take the next tablet at the regular time even if it means taking 2 tabs in 1 day. Abstain from sex or use a back-up form of contraception eg, condom for 7 days.
            Consider the use of another method of contraception eg, injectable, when tablet is continually forgotten.', 0, 59.04, 95, 'tablet', 'box', 28, generate_sku());
$this->insertProduct(17, 'ENERAPLUS SYRUP 60ML', 'Multivitamins', 'Multivitamins', 'Indication: For the treatment of Ener A Plus Drops (Vitamin A (Vitamin A Palmitate)) deficiency.', 0, 36.6, 92, 'bottle', 'box', 1, generate_sku());
$this->insertProduct(17, 'ENERAPLUS SYRUP 120ML', 'Multivitamins', 'Multivitamins', 'Indication: For the treatment of Ener A Plus Drops (Vitamin A (Vitamin A Palmitate)) deficiency.', 0, 51.7, 129.59, 'bottle', 'box', 1, generate_sku());
$this->insertProduct(24, 'FINARID 5MG TAB 30S', 'Finasteride', 'Indications For benign prostatic hyperplasia. \n Dosage  Management of benign prostatic hyperplasia 5 mg daily for ≥6 mth. Male-pattern baldness (alopecia androgenetica) 1 mg daily orally for ≥3 mth.', 0, 103.5, 525, 'tablet', 'box', 30, generate_sku());
$this->insertProduct(24, 'PROTEC FC TAB 28S BOX OF 10S', 'Oflox', 'Indications    OC, menstrual disorders eg dysmenorrhea, premenstrual syndrome, menorrhagia.
    Dosage  1 tab daily for 28 days starting on the 1st day of menstrual cycle.', 0, 100, 330, 'tablet', 'box', 28, generate_sku());
$this->insertProduct(17, 'NAPRAN 100MG/ML DROPS 15ML', 'Paracetamol', 'Indications  Relief of fever & mild to moderate pain.
    Dosage  Susp 125 mg/5 mL Childn 6-12 yr 2-4 tsp; 1-5 yr 1-2 tsp. Susp 250 mg/5 mL Childn 6-12 yr 1-2 tsp; 1-5 yr ½-1 tsp. Oral drops Childn 1-2 yr 1.2-1.8 mL; 7-12 mth 0.6-1.2 mL; 0-6 mth 0.3-0.6 mL. All doses to be taken tid-qid.', 0, 15.6, 37, 'bottle', 'box', 1, generate_sku());
$this->insertProduct(17, 'NAPRAN 125MG/5ML SUSP 60ML', 'Paracetamol', 'Indications  Relief of fever & mild to moderate pain.
    Dosage  Susp 125 mg/5 mL Childn 6-12 yr 2-4 tsp; 1-5 yr 1-2 tsp. Susp 250 mg/5 mL Childn 6-12 yr 1-2 tsp; 1-5 yr ½-1 tsp. Oral drops Childn 1-2 yr 1.2-1.8 mL; 7-12 mth 0.6-1.2 mL; 0-6 mth 0.3-0.6 mL. All doses to be taken tid-qid.', 0, 17.5, 47, 'bottle', 'box', 1, generate_sku());

$this->insertProduct(17, 'NATALIE PRENATAL VIT CAP 30', 'Multivitamins', 'Indications Dietary supplement to provide proper nourishment needed during pre- & post crucial stages of pregnancy. Dosage  1 cap once daily.', 0, 60.2, 242, 'tablet', 'box', 30, generate_sku());
$this->insertProduct(12, 'PIMAX 400MCG ENTERIC-COATED TAB 30S', 'Tamsulosin HCl', 'Indications: Treatment of benign prostatic hyperplasia to relieve symptoms of urinary obstruction. \n Dosage: 200/400 mcg once daily.', 1, 104.6, 693, 'tablet', 'box', 30, generate_sku());

}


public function insertProduct($id, $subcategory_id, $name, $generic_name, $description,
   $prescription_required, $unit_cost, $price, $unit, $packing, $qty_per_packing, $sku ){

   $product = new Product;
   $product->subcategory_id = $subcategory_id;
   $product->name = $name;
   $product->generic_name = $generic_name;
   $product->description = $description;
   $product->prescription_required = $prescription_required;
   $product->unit_cost = $unit_cost
   $product->price = $price;
   $product->unit = $unit;
   $product->packing = $packing;
   $product->qty_per_packing = $qty_per_packing;
   $product->critical_stock = 10;
   $product->sku = $sku;

   $product->save();
}
}
