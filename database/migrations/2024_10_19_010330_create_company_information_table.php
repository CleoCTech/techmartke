<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_information', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string("company_name");
            $table->string("short_name")->nullable();
            $table->date("establishment_date")->nullable();
            $table->mediumText("history",3000)->nullable();
            $table->string("highlight",3000)->nullable();
            $table->mediumText("vision",3000)->nullable();
            $table->mediumText("mission",3000)->nullable();
            $table->string("motto")->nullable();
            $table->mediumText("core_values")->nullable();
            $table->string("location");
            $table->string("about_short",1000)->nullable();
            $table->integer("total_people_helped")->default(0);
            $table->mediumText("about",3000)->nullable();
            $table->string("about_newsletter")->nullable();
            $table->string("emails");
            $table->string("branches")->nullable();
            $table->string("total_members");
            $table->string("phone_numbers");
            $table->string("address")->nullable();
            $table->string("logo")->nullable();
            $table->integer("status")->default(2);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        DB::table('company_information')->insert(
            [
                [
                    'uuid' => (string) Str::uuid(),
                    'company_name' => 'Corner Stone Church',
                    'short_name' => 'Corner Stone Church',
                    'history' => 'Corner Stone Church was founded in 2001. It was founded on a strong Christian background emphasizing high discipline standards and good morals of memembers.',
                    'about_short' => 'We are many variations of passages available but the majority have suffered alteration in some form by injected humour words believable.',
                    'vision' => 'To transform our communities by graduating learners who are spiritually equipped, academically prepared and with good character to be responsible citizens and leaders',
                    'mission' => 'To provide a conducive environment for learning and offer quality excellent and holistic education that develops the whole child spiritually, academically, socially and creatively',
                    'motto' => 'In pursuit of knowledge of God and Truth.',
                    'location' => 'Kisii,Kenya',
                    'emails' => 'info@con-jss.com',
                    'phone_numbers' => "0727057310 or 0751019142",
                    'address' => "661-00300 Kisii",
                    'about_newsletter' => "Subscribe Our Newsletter To Get Latest Update And News",
                    'branches' => "8",
                    'total_members' => "50K+",
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_information');
    }
};
