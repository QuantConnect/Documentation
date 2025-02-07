<?
$library = "Bokeh";
$imports = "# Import the Bokeh library for plots and settings.
from bokeh.plotting import figure, show
from bokeh.models import BasicTicker, ColorBar, ColumnDataSource, LinearColorMapper
from bokeh.palettes import Category20c
from bokeh.transform import cumsum, transform
from bokeh.io import output_notebook

# Call the `output_notebook` method for displaying the plots in the jupyter notebook.
output_notebook()";

include(DOCS_RESOURCES."/research-guide/charting/python-preparation.php");
?>
