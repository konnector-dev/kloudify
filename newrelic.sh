export NR_NAME="newrelic-php5-8.5.0.235-linux"
# extract in current directory
curl -O https://download.newrelic.com/php_agent/release/${NR_NAME}.tar.gz && \
    tar xvf ${NR_NAME}.tar.gz && \
    export NR_INSTALL_USE_CP_NOT_LN=1 && export NR_INSTALL_SILENT=1 && \
    ./${NR_NAME}/newrelic-install install
rm -rf ${NR_NAME}*

export NR_LICENCE=NEWRELIC_LICENSE
sed -i -e 's/"{$NR_LICENCE}"/"abcd"/' \
 -e 's/newrelic.appname = "PHP Application"/newrelic.appname = "Test test"/' \
 /usr/local/etc/php/conf.d/newrelic.ini
