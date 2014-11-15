<?php
require_once '../phpQuery.php';

function get_players_from_bits($firstname, $lastname, $club, $licens_number) {
         static $postdata = array();
//                '__EVENTTARGET' => '',
//                '__EVENTARGUMENT' => '',
//                '__LASTFOCUS' => '',
         $postdata['__VIEWSTATE'] = '/wEPDwUKMjEzMzI3MTcyOQ9kFgJmD2QWAgIDD2QWCgIFD2QWBAIBD2QWAgIBDzwrAA0BCRAWABYAZGQCFQ9kFgICAQ8PFgIeBFRleHQFGDIxOCw4NDxicj5Kb2FraW0gTm9yZMOpbmRkAhEPFgIeB1Zpc2libGVnFgICBw8WAh4FY2xhc3MFCSBzZWxlY3RlZGQCEw9kFgICAQ9kFgICAw9kFgICAQ88KwAJAQAPFgIeDU5ldmVyRXhwYW5kZWRnZGQCFQ9kFgRmDxYCHgVzdHlsZQUicGFkZGluZy1ib3R0b206MC41ZW07ZGlzcGxheTpub25lOxYEAgEPDxYCHwFoZGQCAw8PFgIfAWhkZAICDxYCHwQFDWRpc3BsYXk6bm9uZTsWBAIBDw8WAh8AZRYCHwQFCWRpc3BsYXk6O2QCAw8WAh8ABQM8cD5kAhkPZBYCAgEPZBYEAgEPZBYEAhUPEGRkFgBkAh8PEA8WAh4LXyFEYXRhQm91bmRnZBAVsQEAEUFjdGlvbiBKdWJhbCAyMDEzCkFnZGEtTWl4ZW4hQUlLIEludGVybmF0aW9uYWwgVG91cm5hbWVudCAyMDE0D0Jhcm9tZXRlcm4gMjAxNBBCSyBWODpzIFTDpHZsaW5nEEJsZWtpbmdlIFN0cmlrZW4OQm93bGluZ2ZpZ2h0ZW4IQlQtQ3VwZW4MQsOla2VuIE1peGVuGENhc2hiYWNrdG91ciBFdXJvYm93bCBIVBhDYXNoYmFja3RvdXIgRXVyb2Jvd2wgVlQXQ2FzaGJhY2t0b3VyIE9seW1waWEgSFQYQ2FzaGJhY2t0b3VyIFBhcnRpbGxlIEhUH0Nhc2hiYWNrdG91ciBTdHJpa2UgJiBDbyBnYmcgSFQjQ2FzaGJhY2t0b3VyIFN0cmlrZSAmIENvIMOWcmVicm8gSFQOQ3liZXItWm9uZSBDdXAXRGFsYW1hc3RlcnMgMiAtIEx1ZHZpa2EZRGFsYW1hc3RlcnMgMyAtIEJvcmzDpG5nZRhEYWxhbWFzdGVycyA0IC0gSGVkZW1vcmENREJTIE9wZW4gMjAxMxxETSAzLW1hbm5hIC0gQm9odXNsw6RuIC0gRGFsFERNIDMtbWFubmEgLSBEYWxhcm5hFkRNIDMtbWFubmEgLSBHw7Z0ZWJvcmcZRE0gMy1tYW5uYSAtIEjDpGxzaW5nbGFuZBNETSAzLW1hbm5hIC0gTsOkcmtlE0RNIDMtbWFubmEgLSBTa8OlbmUWRE0gMy1tYW5uYSAtIFbDpHJtbGFuZBpETSAzLW1hbm5hIC0gVsOkc3RlcmJvdHRlbhxETSAzLW1hbm5hIC0gVsOkc3RlcmfDtnRsYW5kGURNIDMtbWFubmEgLSBWw6RzdG1hbmxhbmQgRE0gMy1tYW5uYSBkYW1lciAtIMOFbmdlcm1hbmxhbmQhRE0gMy1tYW5uYSBoZXJyYXIgLSDDhW5nZXJtYW5sYW5kD0RNIEhLUCAtIFNrw6VuZRJETSBIS1AgLSBTdG9ja2hvbG0ZRE0gSW5kaXZpZHVlbGxhIC0gRGFsYXJuYRhETSBJbmRpdmlkdWVsbGEgLSBOw6Rya2UfRE0gSW5kaXZpZHVlbGxhIC0gVsOkc3RlcmJvdHRlbiFETSBJbmRpdmlkdWVsbGEgLSBWw6RzdGVyZ8O2dGxhbmQfRE0gSW5kaXZpZHVlbGxhIERhbSAtIFbDpHJtbGFuZCVETSBJbmRpdmlkdWVsbGEgZGFtZXIgLSDDhW5nZXJtYW5sYW5kIERNIEluZGl2aWR1ZWxsYSBIZXJyIC0gVsOkcm1sYW5kJERNIEluZGl2aWR1ZWxsYSBoZXJyIC0gw4VuZ2VybWFubGFuZCJETSBJbmRpdmlkdWVsbGEvU2VuaW9yIC0gR8O2dGVib3JnMkRNIEluZGl2aWR1ZWxsYS9TZW5pb3IvSnVuaW9yIFNtw6VsYW5kL0JsZWtpbmdlIEJGEERNIEp1bmlvci9TZW5pb3IaRE0gSnVuaW9yL1NlbmlvciAtIERhbGFybmEZRE0gSnVuaW9yL1VuZ2RvbSAtIFNrw6VuZRxETSBKdW5pb3IvVW5nZG9tIC0gVsOkcm1sYW5kIERNIEp1bmlvci9Vbmdkb20gLSBWw6RzdGVyYm90dGVuIERNIEp1bmlvci9Vbmdkb20gLSDDhW5nZXJtYW5sYW5kEkRNIE1peGVkIC0gRGFsYXJuYRJETSBNaXhlZCAtIFVwcGxhbmQYRE0gTWl4ZWQgLSBWw6RzdGVyYm90dGVuGkRNIE1peGVkIC0gVsOkc3RlcmfDtnRsYW5kGERNIE1peGVkIC0gw4VuZ2VybWFubGFuZBVETSBTZW5pb3IgLSBWw6RybWxhbmQZRE0gU2VuaW9yIC0gVsOkc3RlcmJvdHRlbhlETSBTZW5pb3IgLSDDhW5nZXJtYW5sYW5kI0VCVCAxOCAtIDJuZCBGbG9yZW5jZSBJbnRlcm5hdGlvbmFsDEVkc2J5ZHViYmVsbh5FbGphcyBNb3RvciBEYWxhdG91cmVuIC0gRmFsdW4hRWxqYXMgTW90b3IgRGFsYXRvdXJlbiAtIEhlZGVtb3JhIEVsamFzIE1vdG9yIERhbGF0b3VyZW4gLSBMdWR2aWthH0VsamFzIE1vdG9yIERhbGF0b3VyZW4gLSBNYWx1bmcdRWxqYXMgTW90b3IgRGFsYXRvdXJlbiAtIE1vcmEoRWxqYXMgTW90b3IgRGFsYXRvdXJlbiBGaW5hbCAtIEJvcmzDpG5nZQ9FdmEtVHVybmVyaW5nZW4KRnJ1a3RtaXhlbgxHYW1zZW5zbGFnZXQZR0JHIFRvdXIgZGVsIDEgLSBLdW5nw6RsdhpHQkcgVG91ciBkZWwgMiAtIEVyaWtzYmVyZxhHQkcgVG91ciBkZWwgMiBFcmlrc2JlcmcZR0JHIFRvdXIgZGVsIDQgLSBNw7ZsbmRhbBZHQkcgVG91ciBkZWwgNSAtIEtpbm5hD0dldmFsaWEtU3RyaWtlbhJHbyBmb3Igc3RyaWtlIG9wZW4KR3VsZGpha3RlbhBHw6RzdGFidWRzc2xhZ2V0J0hhbGxtw6RzdGVyc2thcGV0IGkgR2V0aW5nZSBCb3dsaW5naGFsbAlIb3ZzbGFnZXQYSMOkbHNpbmdldG91cmVuIEJvbGxuw6RzFkjDpGxzaW5nZXRvdXJlbiBFZHNieW4aSMOkbHNpbmdldG91cmVuIEh1ZGlrc3ZhbGwiSMOkbHNpbmdldG91cmVuIFN0b3JhIEZpbmFsZW4gMjAxNBpIw6Rsc2luZ2V0b3VyZW4gU8O2ZGVyaGFtbhZIw6Rzc2xlaG9sbXNNaXhlbiAyMDEzCUp1bGdyaXNlbgpKdWxza2lua2FuH0p1bmlvciBNYXN0ZXJzIDEzLzE0IEVza2lsc3R1bmEeSnVuaW9yIE1hc3RlcnMgMTMvMTQgRmluYWwgR2JnHkp1bmlvciBNYXN0ZXJzIDEzLzE0IEfDtnRlYm9yZx5KdW5pb3IgTWFzdGVycyAxMy8xNCBLYXJsc2tvZ2EcSnVuaW9yIE1hc3RlcnMgMTMvMTQgU2vDtnZkZR5KdW5pb3IgTWFzdGVycyAxMy8xNCBTdG9ja2hvbG0cSnVuaW9yIE1hc3RlcnMgMTMvMTQgw5ZyZWJybxNLYWxpeCBOb3Jkc2rDtiBPcGVuDUt1bmfDpGx2IE9wZW4cTWl0dC1Ub3VyZW4gMjAxMy0xNCAtIEJpcnN0YSFNaXR0LVRvdXJlbiAyMDEzLTE0IC0gSMOkcm7DtnNhbmQdTWl0dC1Ub3VyZW4gMjAxMy0xNCAtIEp1bnNlbGUgTWl0dC1Ub3VyZW4gMjAxMy0xNCAtIE5vcmRpbmdyw6UgTWl0dC1Ub3VyZW4gMjAxMy0xNCAtIFNvbGxlZnRlw6UkTWl0dC1Ub3VyZW4gMjAxMy0xNCAtIMOWcm5za8O2bGRzdmlrKk1pdHQtVG91cmVuIDIwMTMtMTQgRmluYWwgLSDDlnJuc2vDtmxkc3ZpaxJNb2VsdmVuIFRvdXJuYW1lbnQYTm9ycnRvdXJlbiAxMy8xNCAtIEJvZGVuGU5vcnJ0b3VyZW4gMTMvMTQgLSBQaXRlw6UmTm9ycnRvdXJlbiAxMy8xNCAtIFNlbWlmaW5hbCBvY2ggRmluYWweTm9ycnRvdXJlbiAxMy8xNCAtIFNrZWxsZWZ0ZcOlD05vcnJ0w6RsamVtaXhlbhNOw7ZqZXNmYWJyaWtlbiBPcGVuF09yaWdvIExhZGllcyBUb3VybmFtZW50C1BpdGXDpSBPcGVuD1JpbmtlYnkgQm9vc3RlchNSw6VzdW5kYSBUb3VybmFtZW50DVNhbHTDti1TbGFnZXQeU2VuaW9yIFRvdXIgMTMvMTQgLSBFc2tpbHN0dW5hGVNlbmlvciBUb3VyIDEzLzE0IC0gRmluYWwpU2VuaW9yIFRvdXIgMTMvMTQgLSBHw7Z0ZWJvcmcgU3RyaWtlICYgQ28cU2VuaW9yIFRvdXIgMTMvMTQgLSBOw6Rzc2rDth1TZW5pb3IgVG91ciAxMy8xNCAtIFZpbGJlcmdlbh5TZW5pb3IgVG91ciAxMy8xNCAtIMOEbmdlbGhvbG0IU2lnbWFsZW4mU2vDpW5lbcOkc3RlcnNrYXBldCAyMDEzIC0gSMOkc3NsZWhvbG0fU2vDpW5lbcOkc3RlcnNrYXBldCAyMDEzIC0gTHVuZB9Ta8OlbmVtw6RzdGVyc2thcGV0IDIwMTMgLSBPc2J5IlNrw6VuZW3DpHN0ZXJza2FwZXQgMjAxMyAtIFN2ZWRhbGEeU2vDpW5lcyBVMjMgRGVsIDEgLSDDhG5nZWxob2xtGFNrw6VuZXMgVTIzIERlbCAyIC0gQmp1dh9Ta8OlbmVzIFUyMyBEZWwgMyAtIEjDpHNzbGVob2xtHFNrw6VuZXMgVTIzIERlbCA0IC0gUGVyc3RvcnAbU2vDpW5ldG91cmVuIGRlbCAxIC0gSMO2cmJ5IFNrw6VuZXRvdXJlbiBkZWwgMiAtIEhlbHNpbmdib3JnIFNrw6VuZXRvdXJlbiBkZWwgMyAtIEjDpHNzbGVob2xtH1Nrw6VuZXRvdXJlbiBkZWwgNCAtIMOEbmdlbGhvbG0qU2vDpW5ldG91cmVuIFN1cGVyZmluYWwgMjAxNCAtIEjDpHNzbGVob2xtGFNtw6VsYW5kc3RvdXJlbiAtIEJvcsOlcxhTbcOlbGFuZHN0b3VyZW4gLSBFa3Nqw7YjU23DpWxhbmRzdG91cmVuIC0gSsO2bmvDtnBpbmcgQXJlbmEaU23DpWxhbmRzdG91cmVuIC0gTsOkc3Nqw7YjU23DpWxhbmRzdG91cmVuIC0gU3VwZXJmaW5hbCBCb3LDpXMaU23DpWxhbmRzdG91cmVuIC0gVsOkcm5hbW8YU1RNIDIwMTMgLSBCaXJrYXRyw6RmZmVuElNUTSAyMDEzIC0gRmluYWxlchpTVE0gMjAxMyAtIEd1bGxtYXJzZmlnaHRlbhdTVE0gMjAxMyAtIEjDtnN0c2xhbnRlbh5TVE0gMjAxMyAtIE3DpGxhcm3DpHN0ZXJza2FwZXQZU1RNIDIwMTMgLSBWw6RzYnl0csOkZmZlbhxTVE0gMjAxMyAtIMOFdGVya3ZhbCBIY3AvRGFtHVNUTSAyMDEzIC0gw4V0ZXJrdmFsIE9wZW4vNTUrIVN0b2NraG9sbSBKdW5pb3IgQ2hhbGxhbmdlciAgR1AgMyFTdG9ja2hvbG0gSnVuaW9yIENoYWxsYW5nZXIgIEdQIDQhU3RvY2tob2xtIEp1bmlvciBDaGFsbGFuZ2VyICBHUCA1IVN0b2NraG9sbSBKdW5pb3IgQ2hhbGxhbmdlciAgR1AgNiFTdG9ja2hvbG0gSnVuaW9yIENoYWxsYW5nZXIgIEdQIDchU3RvY2tob2xtIEp1bmlvciBDaGFsbGFuZ2VyICBHUCA4JVN0b2NraG9sbSBKdW5pb3IgQ2hhbGxhbmdlciAgR1AgRmluYWwpU3RvY2tob2xtIEp1bmlvciBDaGFsbGFuZ2VyICBHUCBTZW1pZmluYWwNU3RyYWprIE1hcnRlbh5TdmVuc2thIE3DpHN0ZXJza2FwZW4gLSAgTWl4ZWQfVGFpZi1NaXhlbiAyMDEzIDMwLcOlcnNqdWJpbGV1bR5UZWFtIFgtQ2FsaWJ1ciBUb3VybmFtZW50IDIwMTMHVC1NaXhlbgxUcmV0dG9ubWl4ZW4mVXBwbGFuZHMgSnVuaW9yIFRvdXIgZGVsIDIgLSBFbmvDtnBpbmcnVXBwbGFuZHMgSnVuaW9yIFRvdXIgZGVsIDMgLSBOb3JydMOkbGplJFVwcGxhbmRzIEp1bmlvciBUb3VyIGRlbCA0IC0gQsOlbHN0YSxVcHBsYW5kcyBKdW5pb3IgVG91ciBkZWwgRmluYWwgLSBCb3dsLU8tUkFtYRpVcHBsYW5kcyBSYW5raW5nZmluYWwgMjAxNBNWYWRzYm9tw6RzdGVyc2thcGVuDFZpcnZlbG4gT3BlbhtWLXRvdXJlbiAyIC0gQmVsbGV2dWVzbGFnZXQLVsOlcmJ5bWl4ZW4RVsOlcmJ5c2xhZ2V0IDIwMTQNWXR0ZXJieXNsYWdldA3DhGx2YXRyw6RmZmVuFbEBFTA7MDAwMS0wMS0wMSAwMDowMDowMBgyNjA2OzIwMTMtMTEtMDggMDA6MDA6MDAYMjQ5OTsyMDEzLTA4LTI2IDAwOjAwOjAwGDI1Nzg7MjAxMy0xMi0yNiAwMDowMDowMBgyNTMyOzIwMTQtMDUtMjQgMDA6MDA6MDAYMjYzNzsyMDEzLTEwLTI4IDAwOjAwOjAwGDI1MDE7MjAxMy0xMC0yOSAwMDowMDowMBgyNDk4OzIwMTQtMDItMTQgMDA6MDA6MDAYMjY3NjsyMDEzLTEyLTIxIDAwOjAwOjAwGDI0NzI7MjAxNC0wNC0xMyAwMDowMDowMBgyNTU4OzIwMTMtMDktMDYgMDA6MDA6MDAYMjU2MTsyMDE0LTAxLTAxIDAwOjAwOjAwGDI1ODY7MjAxMy0xMC0wOCAwMDowMDowMBgyNTU5OzIwMTMtMDktMDggMDA6MDA6MDAYMjU2MDsyMDEzLTA5LTA4IDAwOjAwOjAwGDI1NjY7MjAxMy0wOS0xMSAwMDowMDowMBgyNTAyOzIwMTQtMDItMTEgMDA6MDA6MDAYMjYyNTsyMDEzLTEyLTA3IDAwOjAwOjAwGDI2MjY7MjAxNC0wMy0yMyAwMDowMDowMBgyNjI3OzIwMTQtMDUtMTggMDA6MDA6MDAYMjY0NzsyMDEzLTEyLTAyIDAwOjAwOjAwGDI2NDg7MjAxMy0xMi0wNyAwMDowMDowMBgyNjA5OzIwMTMtMTEtMDIgMDA6MDA6MDAYMjY2NzsyMDEzLTEyLTAyIDAwOjAwOjAwGDI1MzM7MjAxMy0xMi0wMSAwMDowMDowMBgyNjY0OzIwMTMtMTItMTUgMDA6MDA6MDAYMjU0MjsyMDEzLTEyLTE0IDAwOjAwOjAwGDI1OTA7MjAxMy0xMC0zMCAwMDowMDowMBgyNjc5OzIwMTMtMTEtMTAgMDA6MDA6MDAYMjU4MTsyMDEzLTExLTAxIDAwOjAwOjAwGDI2MzQ7MjAxMy0xMC0zMSAwMDowMDowMBgyNTM2OzIwMTMtMTItMDcgMDA6MDA6MDAYMjUzNTsyMDEzLTEyLTA4IDAwOjAwOjAwGDI1NDM7MjAxMy0xMi0xNCAwMDowMDowMBgyNTY4OzIwMTMtMTItMDcgMDA6MDA6MDAYMjYxMTsyMDE0LTAyLTE1IDAwOjAwOjAwGDI2NjU7MjAxNC0wMi0xNSAwMDowMDowMBgyNjQxOzIwMTMtMTEtMzAgMDA6MDA6MDAYMjYyMzsyMDE0LTAyLTE1IDAwOjAwOjAwGDI2MDg7MjAxMy0xMi0xNCAwMDowMDowMBgyNTM5OzIwMTMtMTEtMTAgMDA6MDA6MDAYMjYwNzsyMDEzLTEyLTE0IDAwOjAwOjAwGDI1Mzg7MjAxMy0xMS0wOSAwMDowMDowMBgyNjY4OzIwMTQtMDMtMTcgMDA6MDA6MDAYMjYzMzsyMDEzLTEyLTA3IDAwOjAwOjAwGDI2NjY7MjAxNC0wMi0xNiAwMDowMDowMBgyNjEyOzIwMTMtMTItMDcgMDA6MDA6MDAYMjU0NDsyMDEzLTEwLTMxIDAwOjAwOjAwGDI1OTE7MjAxMy0xMS0wNiAwMDowMDowMBgyNjQyOzIwMTMtMTEtMzAgMDA6MDA6MDAYMjU0MDsyMDEzLTEyLTE0IDAwOjAwOjAwGDI2MTA7MjAxNC0wMS0xMSAwMDowMDowMBgyNjM5OzIwMTMtMTEtMDkgMDA6MDA6MDAYMjY0MDsyMDEzLTExLTMwIDAwOjAwOjAwGDI1ODA7MjAxMy0xMC0wNSAwMDowMDowMBgyNTM3OzIwMTQtMDEtMTEgMDA6MDA6MDAYMjU5MjsyMDEzLTExLTA2IDAwOjAwOjAwGDI2ODA7MjAxMy0xMS0zMCAwMDowMDowMBgyNTQxOzIwMTMtMTItMTQgMDA6MDA6MDAYMjUxMzsyMDEzLTExLTAyIDAwOjAwOjAwGDI1ODg7MjAxMy0xMC0yNyAwMDowMDowMBgyNjAyOzIwMTQtMDQtMjEgMDA6MDA6MDAYMjU5ODsyMDEzLTEyLTA5IDAwOjAwOjAwGDI1OTk7MjAxMy0xMi0zMCAwMDowMDowMBgyNjAxOzIwMTQtMDMtMTcgMDA6MDA6MDAYMjYwMDsyMDE0LTAyLTEwIDAwOjAwOjAwGDI2MDM7MjAxNC0wNS0yNCAwMDowMDowMBgyNjc4OzIwMTQtMDItMDcgMDA6MDA6MDAYMjYzNTsyMDEzLTEwLTI3IDAwOjAwOjAwGDI2MDQ7MjAxMy0xMS0wOCAwMDowMDowMBgyNTc3OzIwMTMtMDktMjQgMDA6MDA6MDAYMjU3MjsyMDEzLTA5LTIzIDAwOjAwOjAwGDI1NjQ7MjAxMy0wOS0yMyAwMDowMDowMBgyNjE5OzIwMTMtMTEtMDcgMDA6MDA6MDAYMjYyMDsyMDEzLTExLTA3IDAwOjAwOjAwGDI2NDk7MjAxMy0xMi0wMiAwMDowMDowMBgyNjUzOzIwMTMtMTItMDkgMDA6MDA6MDAYMjY3MzsyMDEzLTEyLTE2IDAwOjAwOjAwGDI2NzE7MjAxMy0xMi0wOSAwMDowMDowMBgyNjMyOzIwMTMtMTEtMDEgMDA6MDA6MDAYMjYyOTsyMDEzLTEwLTEzIDAwOjAwOjAwGDI1NDc7MjAxNC0wMS0xNSAwMDowMDowMBgyNTQ4OzIwMTQtMDItMTUgMDA6MDA6MDAYMjU0NTsyMDEzLTEwLTAxIDAwOjAwOjAwGDI1NDk7MjAxNC0wNS0zMSAwMDowMDowMBgyNTQ2OzIwMTMtMTAtMTUgMDA6MDA6MDAYMjYzNjsyMDEzLTEwLTI3IDAwOjAwOjAwGDI2NTQ7MjAxMy0xMi0wOSAwMDowMDowMBgyNjcyOzIwMTMtMTItMTcgMDA6MDA6MDAYMjU1NTsyMDE0LTA0LTExIDAwOjAwOjAwGDI1NTc7MjAxNC0wNi0wOSAwMDowMDowMBgyNTU2OzIwMTQtMDUtMDYgMDA6MDA6MDAYMjU1MjsyMDEzLTEyLTAzIDAwOjAwOjAwGDI1NTE7MjAxMy0xMC0yOSAwMDowMDowMBgyNTUzOzIwMTQtMDItMTAgMDA6MDA6MDAYMjU1NDsyMDE0LTAzLTA0IDAwOjAwOjAwGDI2NzQ7MjAxMy0xMi0xNiAwMDowMDowMBgyNjIyOzIwMTQtMDItMDMgMDA6MDA6MDAYMjY1OTsyMDE0LTAxLTAxIDAwOjAwOjAwGDI2NjA7MjAxNC0wMi0wMSAwMDowMDowMBgyNjYyOzIwMTQtMDQtMDEgMDA6MDA6MDAYMjY1ODsyMDEzLTExLTAxIDAwOjAwOjAwGDI2NjE7MjAxNC0wMy0wMSAwMDowMDowMBgyNjU3OzIwMTMtMTAtMDEgMDA6MDA6MDAYMjY2MzsyMDE0LTA1LTI1IDAwOjAwOjAwGDI1Njk7MjAxMy0xMS0wMSAwMDowMDowMBgyNDYxOzIwMTMtMTEtMDQgMDA6MDA6MDAYMjQ2MjsyMDE0LTAyLTE3IDAwOjAwOjAwGDI0NjQ7MjAxNC0wNS0xMyAwMDowMDowMBgyNDYzOzIwMTQtMDQtMzAgMDA6MDA6MDAYMjY1MDsyMDEzLTEyLTAyIDAwOjAwOjAwGDI1NzE7MjAxMy0wOS0xOCAwMDowMDowMBgyNjc1OzIwMTMtMTItMTMgMDA6MDA6MDAYMjQ3MDsyMDEzLTEwLTIxIDAwOjAwOjAwGDI1ODc7MjAxMy0xMC0xNiAwMDowMDowMBgyNTgzOzIwMTMtMTAtMjIgMDA6MDA6MDAYMjUwMzsyMDE0LTA0LTIyIDAwOjAwOjAwGDI0ODg7MjAxMy0xMi0yNCAwMDowMDowMBgyNDkxOzIwMTQtMDQtMjcgMDA6MDA6MDAYMjQ4NjsyMDEzLTEwLTIzIDAwOjAwOjAwGDI0ODk7MjAxNC0wMi0wMyAwMDowMDowMBgyNDg3OzIwMTMtMTEtMjcgMDA6MDA6MDAYMjQ5MDsyMDE0LTA0LTA3IDAwOjAwOjAwGDI2NTE7MjAxMy0xMi0wMiAwMDowMDowMBgyNjY5OzIwMTMtMTItMDEgMDA6MDA6MDAYMjYxMzsyMDEzLTEwLTI5IDAwOjAwOjAwGDI2NzA7MjAxMy0xMi0wMSAwMDowMDowMBgyNjE0OzIwMTMtMTAtMjkgMDA6MDA6MDAYMjU5MzsyMDEzLTEwLTI2IDAwOjAwOjAwGDI1OTQ7MjAxMy0xMi0wMiAwMDowMDowMBgyNTk1OzIwMTMtMTItMjkgMDA6MDA6MDAYMjU5NjsyMDE0LTAyLTA5IDAwOjAwOjAwGDI1NzQ7MjAxMy0wOS0yMyAwMDowMDowMBgyNjQzOzIwMTMtMTEtMjUgMDA6MDA6MDAYMjY0NDsyMDE0LTAyLTA5IDAwOjAwOjAwGDI2NDU7MjAxNC0wNC0yMiAwMDowMDowMBgyNjQ2OzIwMTQtMDUtMzEgMDA6MDA6MDAYMjQ5MjsyMDEzLTA4LTEyIDAwOjAwOjAwGDI0OTQ7MjAxMy0xMi0wOSAwMDowMDowMBgyNDk2OzIwMTQtMDMtMTIgMDA6MDA6MDAYMjQ5MzsyMDEzLTA5LTIzIDAwOjAwOjAwGDI0OTc7MjAxNC0wNi0wMiAwMDowMDowMBgyNDk1OzIwMTQtMDItMTAgMDA6MDA6MDAYMjQ4MDsyMDEzLTEyLTA3IDAwOjAwOjAwGDI0ODM7MjAxNC0wMi0xNCAwMDowMDowMBgyNDc3OzIwMTMtMTAtMjMgMDA6MDA6MDAYMjQ3OTsyMDEzLTEyLTAxIDAwOjAwOjAwGDI0Nzg7MjAxMy0xMC0yOCAwMDowMDowMBgyNDc2OzIwMTMtMTAtMjMgMDA6MDA6MDAYMjQ4MTsyMDE0LTAyLTEwIDAwOjAwOjAwGDI0ODI7MjAxNC0wMi0xMSAwMDowMDowMBgyNTIyOzIwMTMtMTEtMDYgMDA6MDA6MDAYMjUyMzsyMDEzLTEyLTA1IDAwOjAwOjAwGDI1MjQ7MjAxNC0wMS0wOSAwMDowMDowMBgyNTI1OzIwMTQtMDItMDYgMDA6MDA6MDAYMjUyNjsyMDE0LTAzLTE4IDAwOjAwOjAwGDI1Mjc7MjAxNC0wNC0yNSAwMDowMDowMBgyNTI5OzIwMTQtMDUtMjQgMDA6MDA6MDAYMjUyODsyMDE0LTA1LTE1IDAwOjAwOjAwGDI0NzE7MjAxMy0xMi0yMiAwMDowMDowMBgyNTg0OzIwMTMtMDktMjMgMDA6MDA6MDAYMjU4OTsyMDEzLTExLTA0IDAwOjAwOjAwGDI2MDU7MjAxMy0xMC0xNCAwMDowMDowMBgyNjc3OzIwMTMtMTItMjkgMDA6MDA6MDAYMjY1NTsyMDEzLTEyLTIyIDAwOjAwOjAwGDI2MTU7MjAxMy0xMS0wNSAwMDowMDowMBgyNjE2OzIwMTQtMDEtMDcgMDA6MDA6MDAYMjYxNzsyMDE0LTAzLTE4IDAwOjAwOjAwGDI2MTg7MjAxNC0wNS0xMyAwMDowMDowMBgyNjI0OzIwMTQtMDUtMTggMDA6MDA6MDAYMjY1NjsyMDE0LTAyLTA3IDAwOjAwOjAwGDI2Mzg7MjAxMy0xMC0yOCAwMDowMDowMBgyNTc1OzIwMTMtMDktMjMgMDA6MDA6MDAYMjU2MzsyMDEzLTA5LTIzIDAwOjAwOjAwGDI1NzA7MjAxNC0wMi0xMSAwMDowMDowMBgyNjIxOzIwMTMtMTItMjEgMDA6MDA6MDAYMjY1MjsyMDEzLTEyLTA3IDAwOjAwOjAwFCsDsQFnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2dnZ2cWAWZkAgMPPCsAEQEBEBYAFgAWAGQYAQU/Y3RsMDAkTWFpbkNvbnRlbnRQbGFjZUhvbGRlciRTZWFyY2hQbGF5ZXIkR3JpZFZpZXdTZWFyY2hMaWNlbmNlD2dkUh7m6CExSInqEICprY6ai7TAP7EJ7SNqaLXhB9fzxJg=';
         $postdata['__EVENTVALIDATION'] = '/wEWwgECkbKpigYC/r7uqg8CzJ2QrgMCmNX/owwCmK34QgLui9K5DQKmo8afAQKIr8jwDwKE5J69AQKw7Y7SDQKOzPLtBQK96f7uCALn6Z7nDALq18XOAgL95a6HAgKR77buAwLLsdXpBwKLsPybAgL3oOyaDwLEx6DtDwKDs9qiAwKP1p63CwLTn9zTBwLE67yJDQL22OSgAgLTt6eQCgKB+4OQCQKjs+XBDQLMkLKxAgKmzpjeDAKmyNT+DALy/sjiAwKC0eXSAgL+/L38CgKOhajPDgKl3LLDBgKwvPe3DQK6loWOCQKjzJ6jCAKNr6IKAunx3LUGAsyKyZkDAvfXzpMOAqOByZYFAufEnLAFAsSPmKEDApGzoYAEAsWB7dcEAsOayJAEAuaQuE4CwrO34wQCn/OY/A8ClLy4yAwC6rzPug8Cx/aYkwMCldaD/QYChoqOwAYCqpncwAkCtPbx7wEC0cjUpwoC/6HjzgwChMv9pQ8CzJPuxAYC5+G1uQQChMaF/QUCyZDypQsC9ubH8w0C752qgQQCuOn43AcCh8eAnQwCtMz9IAKl45mQCgLajoPDBAKhj9a8BwKA34TNDQKumM+7AgLgibNKAq6roaEOAr2fyt8CAoDdqGUC9YnlgAUCxMCS2gQCh9K3gwIC+ZDq/AcCpOa0jwECrKD/lgUC1vOsuQoChLmnpwsCn9rs7AcC46LVnQkC05CknwECmeXuqgcC2sff7QkCuPr4xAwCo67F0QUCpIaBqwsCtP6b+gwClvjfrwQClMjO5gYCv5yewQgCseKW5wgCj9fMmQQC9YLz+Q4C1OL31wwCnvqujgYC5e6SuAkChYKUpAQCoZLa0w4CytOOxgcC0dXR4gQCrdeNuAoCwbOX0QsCufbZkAkCgau7lw4CuIz43gUCg6eBUQLk8eLDCQKnofm1DQLl3r3uBAK15dDKBwLEpMyTAgLjga+PAgKYhe7+DwLg8KOjAgLdkeXvAwKjrqD4AQLXobC0DwLerMuwDgKUqeeDDQK//rymAwK5ovjQDQKSwdiiBwK3+NLjAwLr5f7+CALNjcSjCgLi9sW1BwKcpb3RDwK4u76yBgL2msatDgK34cavAgKtufi/BALfp9e9DwKHs62lDgKd2piCAwKfkP2qCwKi1vLiDAKJ7O+eBAKR+7OpAQLXwu7CAgLm0tvqCALQxuLfCALlkbCCCgK94JHzBAKP1dnNAgL3p9vuDAKJ9ICiDwL4udzIDgKK/Z2nBAK51pXGDQLByeyyDQKTkpbnDAKX4OCyAQK8uOuNBwLbwZToAgLv/IyeCgKjo86vCAKX1bq5DALpycGeBwKImsLHDwLHyonNBALB1/KiAwK98dODBALIu8/YBQLP+duOCgKWo5vZBALG+bf3CwKWs9alCQKYqoP9DgLkvMuRDQKovZsgAqT2nJENAuz8ic4KArKcm44BAqnyuLkJAr2N9JACAq/txvQFAvuHj9ELApe60qoMAqyBsqwMAsuXpagLApDA2IcHAumf9bQKAqvXruoBAtuV5fMGjSShihfaUXibUT3qcQ+QhPt18zm/iEZjm7yopNCI0LM=';
         $postdata['ctl00$MainContentPlaceHolder$SearchPlayer$TextBoxLicNbr'] = $licens_number;
         $postdata['ctl00$MainContentPlaceHolder$SearchPlayer$TextBoxFirstName'] = $firstname;
         $postdata['ctl00$MainContentPlaceHolder$SearchPlayer$TextBoxSurName'] = $lastname;
         $postdata['ctl00$MainContentPlaceHolder$SearchPlayer$TextboxClub'] = $club;
         $postdata['ctl00$MainContentPlaceHolder$SearchPlayer$ButtonSearch'] = '';
         $poststring = '';

         $i = 0;
         foreach ($postdata as $key=>$val) {
             $i++;
             if ($i<=2)
                 $poststring .= $key . '=' . urlencode($val) . '&';
             else
                 $poststring .= $key . '=' . utf8_decode($val) . '&';
//               $postdata[$key] = utf8_encode($val);
//               echo $postdata[$key] . "<br>";
         }

         $poststring = rtrim($poststring,'&');
         $ch = curl_init();
         // set URL and other appropriate options
         curl_setopt($ch, CURLOPT_URL, 'http://bits.swebowl.se/QuerySearchPlayer.aspx');
         curl_setopt($ch, CURLOPT_HEADER, false);
         curl_setopt($ch, CURLOPT_POST, count($postdata));
         curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         // grab URL and pass it to the browser
         $retval = curl_exec($ch);

         $dom = phpQuery::newDocumentHTML($retval);
         
         curl_close($ch);

         $result_arr = array();

         foreach (pq('option') as $opt) {
             if ((substr($opt->getAttribute("value"),0,1) == "K") or (substr($opt->getAttribute("value"),0,1) == "M")) {
//                 echo $opt->getAttribute("value") . " " . $opt->nodeValue . "<br>";
                 $person = array();
                 $name_comma_club = preg_replace("/\s*\(.*$/","",$opt->nodeValue);
                 $person["name"] = preg_replace("/,.*$/","",$name_comma_club);
                 $person["club"] = preg_replace("/^.*,\s*/","",$name_comma_club);
                 $person["licens_number"] = $opt->getAttribute("value");
                 array_push($result_arr,$person);
             }
         }
         echo json_encode($result_arr);
}
?>