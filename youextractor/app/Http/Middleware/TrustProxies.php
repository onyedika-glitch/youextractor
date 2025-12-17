use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = '*';

    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
