import axios from 'axios';
window.axios = axios;
import Cookies from "js-cookie";
window.Cookies = Cookies;
import XLSX from 'xlsx';
window.XLSX = XLSX;
import XlsxPopulate from 'xlsx-populate';
window.XlsxPopulate = XlsxPopulate;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
