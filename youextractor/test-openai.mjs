import OpenAI from "openai";

// Initialize OpenAI client. 
// It will automatically look for the OPENAI_API_KEY environment variable.
const openai = new OpenAI();

async function run() {
  try {
    console.log("Sending request to OpenAI using Responses API...\n");
    const response = await openai.responses.create({
      model: "gpt-5.4-mini",
      input: "Write a haiku about AI.",
      store: true,
    });

    console.log("--- Output ---");
    console.log(response.output_text);
    console.log("--------------");
  } catch (error) {
    console.error("Error executing request:", error);
  }
}

run();
