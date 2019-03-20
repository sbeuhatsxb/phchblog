FROM ubuntu:18.04

# Cf. https://build-me-the-docs-please.readthedocs.io/en/latest/Using_Sphinx/UsingGraphicsAndDiagramsInSphinx.html
# https://pypi.org/project/sphinxcontrib-plantuml/
ENV GRAPHVIZ_DOT=/usr/bin/dot

RUN apt-get update \
    && apt-get -qqy install tzdata \
    && ln -fs /usr/share/zoneinfo/Europe/Paris /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && apt-get -qqy install python-pip latexmk texlive-latex-extra \
    && apt-get -qqy install authbind \
    && apt-get -qqy install graphviz \
    && apt-get -qqy install openjdk-11-jre \
    && touch /etc/authbind/byport/80 \
    && chmod 777 /etc/authbind/byport/80
RUN pip install --upgrade pip
RUN pip install sphinx sphinx-autobuild sphinx_rtd_theme sphinxcontrib-phpdomain \
    && pip install recommonmark \
    && pip install Pygments \
    && pip install sphinxcontrib-plantuml

COPY rootfs /

RUN useradd -ms /bin/bash sphinx

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD []
