====
Home
====

.. toctree::
    :maxdepth: 1
    :glob:

    our-platform/*
    lean-engine/*
    writing-algorithms/*
    research/*

.. raw:: html

    <div class="flex-column qc-doc-header">
        <div class="flex-center docs-margin-small">
            <img src="https://cdn.quantconnect.com/i/tu/documentation-v1.0.png" alt="list-icon" style="width: 25px; height: 23px; margin-right: 15px;">
            <p class="docs-title">QuantConnect Documentation</p>
        </div>
         <p class="docs-p">Learn to use QuantConnect and Explore Our Features</p>
    </div>
    <div class="flex-column">
        <div class="flex-center docs-section-icon docs-margin-small">
            <img src="https://cdn.quantconnect.com/i/tu/list-v1.0.png" alt="list-icon" style="width: 18px; height: 12px; margin-right: 15px;">
            <div id="documentation-sections" class="section"><h2 class="docs-title-small"><a href="#documentation-sections">Documentation Sections</a></h2></div>
        </div>
         <p  class="docs-p" style="margin-bottom: 75px;">QuantConnect’s LEAN engine manages your portfolio and data feeds letting you focus on your algorithm strategy and execution.</p>
    </div>
    <div class="docs-img-row">
        <a href='our-platform/index.html'>
            <div class="flex-center docs-section-icon">
                <span>
                    <img src="https://cdn.quantconnect.com/i/tu/platform-v1.0.png" alt="list-icon" width="60px" height="60px">
                </span>
                <div class="flex-column">
                    <p class="docs-p" style="color: #0072BC">Our Platform</p>
                    <p class="docs-p docs-p-small">Learn the basics of working in the terminal</p>
                </div>
            </div>
        </a>
         <a href='lean-engine/index.html'>
           <div class="flex-center docs-section-icon">
                <span>
                    <img src="https://cdn.quantconnect.com/i/tu/lean-v3.0.png" alt="list-icon" width="65px" height="60px">
                </span>
                <div class="flex-column">
                    <p class="docs-p" style="color: #0072BC">LEAN Engine</p>
                    <p class="docs-p docs-p-small ">Reference to building an Algorithm</p>
                </div>
            </div>
        </a>
    </div>
    <div class="docs-img-row">
        <a href='writing-algorithms/index.html'>
            <div class="flex-center docs-section-icon">
                <span>
                    <img src="https://cdn.quantconnect.com/i/tu/alrgorithms-v2.0.png" alt="list-icon" width="55px" height="60px">
                </span>
                <div class="flex-column">
                    <p class="docs-p" style="color: #0072BC">Writing Algorithms</p>
                    <p class="docs-p docs-p-small">Algorithm Reference</p>
                </div>
            </div>
        </a>
         <a href='research/index.html'>
           <div class="flex-center docs-section-icon">
                <span>
                     <img src="https://cdn.quantconnect.com/i/tu/research-v2.0.png" alt="list-icon" width="40px" height="60px">
                </span>
                <div class="flex-column">
                    <p class="docs-p" style="color: #0072BC">Research</p>
                    <p class="docs-p docs-p-small ">Interactive Jupyter development API
            </p>
                </div>
            </div>
        </a>
    </div>

    <div>
        <div id="try-quantconnect-now" class=" qc-now section" style="margin-bottom: 20px;">
            <h2 class="docs-title-small"><a href="#try-quantconnect-now">Try QuantConnect Now </a></h2>
        </div>
        <p class="docs-p">QuantConnect's LEAN engine manages your portfolio and data feeds letting you focus on your algorithm strategy
            and execution. Data is piped into your strategy via event handlers, upon which you can place trades.
            We provide basic portfolio management and fill modelling underneath the hood automatically. This is provided by the QCAlgorithm base class.</p>
    </div>


|

.. tabs::

   .. code-tab:: c#

        public class BasicTemplateAlgorithm : QCAlgorithm
        {
          public override void Initialize()
          {
             // Setup algorithm requirements: cash, dates and securities.
             // Initialize is called once at the start of the algorithm.
          }

          public override void OnData(Slice data) {
             // Data requested is then piped into event handlers like this one.
          }
        }

   .. code-tab:: py

        class BasicTemplateAlgorithm(QCAlgorithm):
          def Initialize(self):
                '''Initialise the data and resolution required, as well as the
                cash and start-end dates for your algorithm. All algorithms must initialized.'''
                pass

            def OnData(self, data):
                '''OnData event is the primary entry point for your algorithm. Each new data
                point will be pumped in here. data is a Slice object keyed by symbol containing
                the stock data'''
                pass

|

.. raw:: html

    <div class="section">
        <h2 id="supported-brokerages"class="docs-title-small"><a href="#supported-brokerages">Supported Brokerages</a></h2>
    </div>

.. container:: all-brokers

  |interactive-brokers|

  |

  |oanda|

  |

  |fxcm|

  |

  |coinbase|

  |

  |alpaca|

  |

  |bitfinex|

.. |interactive-brokers| image:: https://cdn.quantconnect.com/web/i/splash/Interactive%20Brokers%20Brokerage.png
   :height: 27px

.. |oanda| image:: https://cdn.quantconnect.com/web/i/splash/OANDA%20Brokerage.png
   :height: 27px

.. |fxcm| image:: https://cdn.quantconnect.com/web/i/splash/FXCM%20Brokerage.png
   :height: 27px

.. |coinbase| image:: https://cdn.quantconnect.com/web/i/coinbase_pro_rev0.png
   :height: 27px

.. |alpaca| image:: https://cdn.quantconnect.com/web/i/splash/Alpaca%20Brokerage.png
   :height: 27px

.. |bitfinex| image:: https://cdn.quantconnect.com/web/i/splash/Bitfinex%20Exchange.png
   :height: 27px

.. raw:: html

    <div class="contribution-section">
        <p class="docs-p" style="margin: 30px 0;">You can also see our <span style="color: #0072BC; cursor: pointer;">Tutorials</span> and  <a class="text-bold" target="_BLANK" href="https://www.youtube.com/user/QuantConnect/videos" rel="nofollow"><span style="color: #0072BC; cursor: pointer;">Videos</span></a>. You can also get in touch with us via <a class="text-bold" target="_BLANK" href="https://www.quantconnect.com/slack"><span style="color: #0072BC; cursor: pointer;">Chat</span></a>.</p>
        <p class="docs-p page-helpful" style="margin: 30px 0;">Did you find this page helpful?  <span class="yes-answer" style="color: #0072BC; margin-left: 10px; cursor: pointer;">Yes</span> <span class="no-answer" style="color: #0072BC; margin-left: 10px; cursor: pointer;">No</span></p>
        <a href="https://github.com/QuantConnect/Documentation">
            <div class="flex-center" style="margin: 30px 0; cursor: pointer; display: flex; align-items: center;">
                <p class="docs-p">Contribute to the documentation </p>
                <img src="https://cdn.quantconnect.com/i/tu/github-v1.0.png" alt="quant-connect" style="width: 24px; height: 23px; margin-left: 10px;">
            </div>
        </a>
    </div>