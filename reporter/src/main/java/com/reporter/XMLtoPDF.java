package com.reporter;
import java.io.BufferedOutputStream;

import java.io.File;

import java.io.FileOutputStream;
import java.io.IOException;



import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Source;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;

import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.sax.SAXResult;
import javax.xml.transform.stream.StreamResult;
import javax.xml.transform.stream.StreamSource;


import org.apache.fop.apps.FOPException;

import org.apache.fop.apps.Fop;
import org.apache.fop.apps.FopFactory;
import org.apache.fop.apps.MimeConstants;

import org.w3c.dom.Document;

import org.xml.sax.SAXException;





public class XMLtoPDF {
	
	
	XMLtoPDF()
	{}
	
	
	public void xmlToFO(){
		try{
		DocumentBuilderFactory factory =
		DocumentBuilderFactory.newInstance();
		File stylesheet = new File("src/main/resources/result.xsl");
		File xmlFile = new File("src/main/resources/result.xml");
		DocumentBuilder builder = factory.newDocumentBuilder();
		Document document = builder.parse(xmlFile);
		TransformerFactory transformerFactory = TransformerFactory.newInstance();
		Transformer transformer = transformerFactory.newTransformer(new StreamSource(stylesheet));
		DOMSource source = new DOMSource(document);
		StreamResult result = new StreamResult(new File("src/main/resources/result.fo"));
		transformer.transform(source, result);
		}catch(TransformerConfigurationException e)
		{System.err.println("TransformerConfigurationException: "+e.getMessage());}
		catch(TransformerException e)
		{System.err.println("TransformerException: "+e.getMessage());}
		catch(ParserConfigurationException e)
		{System.err.println("TransformerConfigurationException: "+e.getMessage());}
		catch(IOException e){System.err.println("TransformerException: "+e.getMessage());}
		catch(SAXException e){System.err.println("TransformerException: "+e.getMessage());}
		}
	
	
	
public void	foToPDF() throws IOException, TransformerException, FOPException
{
	FopFactory fopFactory = FopFactory.newInstance();

	// Step 2: Set up output stream.
	// Note: Using BufferedOutputStream for performance reasons (helpful with FileOutputStreams).
	BufferedOutputStream out = new BufferedOutputStream(new FileOutputStream(new File("src/main/resources/result.pdf")));

	try {
	    // Step 3: Construct fop with desired output format
	    Fop fop1 = fopFactory.newFop(MimeConstants.MIME_PDF, out);

	    // Step 4: Setup JAXP using identity transformer
	    TransformerFactory factory = TransformerFactory.newInstance();
	    Transformer transformer = factory.newTransformer(); // identity transformer

	    // Step 5: Setup input and output for XSLT transformation
	    // Setup input stream
	    Source src = new StreamSource(new File("src/main/resources/result.fo"));

	    // Resulting SAX events (the generated FO) must be piped through to FOP
	    SAXResult res = new SAXResult(fop1.getDefaultHandler());

	    // Step 6: Start XSLT transformation and FOP processing
	    transformer.transform(src, res);

	} finally {
	    //Clean-up
	    out.close();
	}
	
	
	
	
	
	
}
	
	
	
	
	
	
	
	
	public static void main(String[] argv) throws FOPException, IOException, TransformerException{
		XMLtoPDF fop=new XMLtoPDF();
		fop.xmlToFO();
		fop.foToPDF();
		
		
		
		
		
		
		
		
			
}	
	

}
