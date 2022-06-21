## Surah Audio Downloder from Quran Central

This repo consists of 2 scripts:

* get.php - this uses the reciters.html file (which is a modified scrape of the quran central reciters page) to download surah audio by reciter. This
* is then separately pushed to the s3 bucket(s).
* cdn.php - this needs the S3 KEY and SECRET. This scans the s3 bucket to get the names of the reciters from the actual files and writes
* cdn_surah_audio.json file, which can be used to programatically gain access to surah audio files. Please see https://alquran.cloud/cdn for more
* information.

Please note that this is not great code that's meant to be reused. It does just one thing and was written fast rather than to cater for re-usability.
Use with caution.
