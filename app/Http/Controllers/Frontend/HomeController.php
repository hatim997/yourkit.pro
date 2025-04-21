<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\EmailTemplate;
use App\Models\NewsletterSubscriber;
use App\Repositories\BannerRepository;
use App\Repositories\BundleRepository;
use App\Repositories\EcommerceRepository;
use App\Repositories\FaqRepository;
use App\Repositories\PageRepository;
use App\Repositories\SubCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail as Email;
use App\Mail\SendEmail;

class HomeController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        // echo getInitials(); die;
        $this->pageRepository = $pageRepository;
    }

    public function index(BannerRepository $bannerRepository, SubCategoryRepository $subcategoryRepository, BundleRepository $bundleRepository, EcommerceRepository $ecommerceRepository)
    {
        $banners = Banner::where('status', 1)->get();
        $subcategories = $subcategoryRepository->get();
        $bundles = $bundleRepository->getBundleProduct(6);
        $products = $ecommerceRepository->getProduct();

        return view('frontend.home', compact('banners', 'subcategories', 'bundles', 'products'));
    }


    public function product(SubCategoryRepository $subcategoryRepository, BundleRepository $bundleRepository)
    {
        $subcategories = $subcategoryRepository->getSubcategoryWithMedia();
        $bundles = $bundleRepository->getBundleProduct();
        return view('frontend.product', compact('subcategories', 'bundles'));
    }


    public function faq(FaqRepository $faqRepository)
    {
        $faqs = $faqRepository->getFaqsWithStatus();
        return view('frontend.faq', compact('faqs'));
    }

    public function terms()
    {

        $page = $this->pageRepository->getPageContentByTag('terms-of-sale');
        return view('frontend.page', compact('page'));
    }

    public function privacy()
    {

        $page =  $this->pageRepository->getPageContentByTag('privacy');
        return view('frontend.page', compact('page'));
    }

    public function return()
    {

        $page =  $this->pageRepository->getPageContentByTag('return-policy');
        return view('frontend.page', compact('page'));
    }


    public function deliveryInfo()
    {

        $page =  $this->pageRepository->getPageContentByTag('delivery-information');
        return view('frontend.page', compact('page'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }


    public function sendMail(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'subscribe_email' => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                //return back()->withErrors($validator)->withInput();
                return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Invalid email address'], 200);
            }
            //checking multiple Submission
            if (!$request->has("idempotency_key") || empty($request->idempotency_key)) {
                return redirect()->back()->withInput();
            }
            if (Cache::has($request->idempotency_key)) {
                return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Duplicate Requests.'], 200);
            }
            //end of checking multiple Submission

            $mail_template      = EmailTemplate::where('email_type', 'newsletter_subscriber')->first();
            $message            = $mail_template->content;
            $search = array('[link]', '[site_name]');
            $replace = array(route("frontend.signin"), \App\Helpers\Helper::getCompanyName());
            $message = str_replace($search, $replace, $message);
            $checkExistsEmail   = NewsletterSubscriber::where('email', $request->subscribe_email)->first();


            if (empty($checkExistsEmail)) {
                $token = hash('sha256', time());
                $mail =   NewsletterSubscriber::create([
                    'email' => $request->subscribe_email,
                    'token' => $token,
                    'status' => 1,

                ]);

                $data['name'] = '';
                $data['email'] = $request->subscribe_email;
                $data['attachments'] = '';
                $data['subject'] = $mail_template->subject;
                $data['content'] =  $message;
                $data['template_file_path'] = "frontend.mails.email_template";
                //$data['email'] = 'atanu.sahoo@sbinfowaves.com';
                Email::to($data['email'])->send(new SendEmail($data));

                //Toastr::success('Mail has been sent successfully', 'Success');
                // Store the idempotency key in the cache
                Cache::put($request->idempotency_key, true, 3600); // Store for 1 hour
                DB::commit();
                return response()->json(['success' => true, 'title' => 'Success', 'message' => 'Success! You are now subscribed.'], 200);
            } else {
                return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Already Exists'], 200);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['success' => false, 'title' => 'Error', 'message' => 'An error Occured. Error:' . $ex->getMessage()], 200);
        }
    }
}
